<?php session_start();
	include('dbcon.php');

	include "baseurl.php";
	
	//include("header.php");
	
?>


<?php
	
	$comid  = $_SESSION['comid'];
	$role	= $_SESSION['role'];
	$user_category	= $_SESSION['user_category'];
	$user   = $_SESSION['user'];
	$usrid	= $_SESSION['usrid'];



if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
//Aproval MEMO Pending						
						$doc_type	= 'AP';
						$pcnt_ap = 0;

								if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account' || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
									$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
									$sql ="SELECT approval_status, count(*) as cnt from sma_approval_memo DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'AP' and status in('Pending', 'Verified','Prepared') 
											 and id in ($id_var) ) DS1 
											 ON DS1.doc_id = DS.id where DS.project in (  $comid  ) and DS.approval_status in('Pending', 'Verified','Prepared')";
								//echo $sql;			 //and reviewed_by = '$usrid'
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt  FROM `sma_approval_memo` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
											select max(id) as id from `workflow_history` where doc_Type = 'AP' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = 'AP' and status in('Pending', 'Verified','Prepared') ) DS1 ON sma_approval_memo.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared') and status = 'Draft' and project in ( $comid ) and draft_by = '$user' ";
								}
								
								if($user == 'Admin'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where approval_status in('Pending', 'Verified','Prepared')  ";
								}
						
								$sql .= " and del !='Y' ";
//echo $sql;
						
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt_ap = $r1['cnt'];
								}
							
//Purchase ORder Pending
						$doc_type	= 'PO';
						$pcnt_po = 0;
								if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account' || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
									$id_var = '';
									$sql = "SELECT max(id) as idd, doc_id FROM `workflow_history` where doc_Type = '$doc_type'  group by doc_id";
									
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
										$doc_id_var .= $rs['doc_id'].',';
									}
									$id_var .= '0';
									$doc_id_var .= '0';
									$sql = "SELECT approval_status, count(*) as cnt from sma_purchase_order DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
										and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') and id in ($doc_id_var) and del!='Y' ";		
								//echo $sql;
									
								}
								else {
									//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_purchase_order` where project in ($comid) and approval_status in('Pending', 'Verified','Prepared')  and draft_by = '$user' ";
									$sql = "SELECT approval_status, count(*) as cnt  FROM `sma_purchase_order` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
											select max(id) as id from `workflow_history` where doc_Type = '$doc_type' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = '$doc_type' and status in('Pending', 'Verified','Prepared') ) DS1 ON sma_purchase_order.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared') and status = 'Draft' and project in ( $comid ) and draft_by = '$user'  and del!='Y'  ";
							//echo $sql;				
								}
								
							if($user == 'Admin'){
								$sql = "SELECT approval_status, count(*) as cnt FROM `sma_purchase_order` where approval_status in('Pending', 'Verified','Prepared')  and del!='Y'  ";
							}
//echo $sql.' ' . $role . "<BR>";
								//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_purchase_order` where approval_status in('Pending', 'Verified')  ";
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt_po = $r1['cnt'];
								}
						
//GRN SRN Pending								
						$doc_type	= 'GS';
						$pcnt_gs = 0;
			
								$pcnt = 0;
								if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account' || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
									//$sql="SELECT approval_status, count(*) as cnt from sma_grn_srn where   approval_status in('Pending', 'Verified','Prepared')  
									//	and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'GS'   and status in('Pending', 'Verified','Prepared') ) ) ";
									$id_var = '';
								/*	$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type'  group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
								*/	
									$id_var .= '0';
									$sql = "SELECT approval_status, count(*) as cnt from sma_grn_srn DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
											and id in ($id_var ) ) DS1 
											ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Prepared')  and DS.del!='Y' ";	
								}
								else {
									//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where  approval_status in('Pending', 'Verified','Prepared')  and draft_by = '$user' ";
									$sql = "SELECT approval_status, count(*) as cnt  FROM `sma_grn_srn` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
											select max(id) as id from `workflow_history` where doc_Type = 'GS' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = 'GS' and status in('Pending', 'Verified','Prepared') ) DS1 ON sma_grn_srn.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared') and status = 'Draft' and draft_by = '$user' and del!='Y'  ";
								}
								
							if($user == 'Admin'){
								$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where  approval_status in('Pending', 'Verified','Prepared')  and del!='Y'  ";
							}
							
//echo $sql."<BR>";
								//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where approval_status in('Pending', 'Verified')  ";
							/*	$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt_gs = $r1['cnt'];
								}
							*/	

//Suuplier Invoice Pending								
						$doc_type	= 'SI';
						$pcnt_si = 0;
//echo $role;			
						$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
									
						if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account' || $role == 'HOD' || $role == 'CXO' ){
									$sql = "SELECT approval_status, count(*) as cnt from sma_supplier_invoice DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared', 'Submited') 
											and id in ($id_var) ) DS1 
											ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared')  and DS.del!='Y'  ";									
//echo $sql;
								}
								else if ($role =='Accountant' || $role =='Checker - Account' ){
									$sql = "SELECT approval_status, count(*) as cnt from sma_supplier_invoice DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared','Submited') 
											and id in ($id_var) ) DS1 
											ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared')  and DS.del!='Y'  ";	
											
								}
								else if (  $role =='Checker - Account' || $role =='HOD - Account' || $role =='Maker' ){
									$sql = "SELECT approval_status, count(*) as cnt from sma_supplier_invoice where company_id in ( $comid ) and  approval_status in('Pending', 'Verified','Prepared') and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = '$doc_type') )  and del!='Y' ";
									
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt  FROM `sma_supplier_invoice` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
											select max(id) as id from `workflow_history` where doc_Type = '$doc_type' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = '$doc_type' and status in('Pending', 'Verified','Prepared','Submited') ) DS1 ON sma_supplier_invoice.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared') and status = 'Draft' and company_id in ( $comid ) and draft_by = '$user'  and del!='Y'  ";
								}
								
							
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt_si = $r1['cnt'];
								}
						
						
//IPC Pending								
								$doc_type	= 'IP';
								
								$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
									
								$pcnt_ip = 0;
								if ( $role =='Accountant' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account' || $role == 'HOD' || $role == 'CXO'){
									$sql="SELECT approval_status, count(*) as cnt  from sma_ipc where sma_comp_id in ( $comid ) and  approval_status in('Pending', 'Verified','Prepared') and (id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type'  ) or draft_by = '$user' )  ";
					//echo $sql;					
								}
								else {
									//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_ipc` where sma_comp_id in ($comid) and approval_status in('Pending', 'Verified','Prepared') and draft_by = '$user' ";
									$sql = "SELECT approval_status, count(*) as cnt  FROM `sma_ipc` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
											select max(id) as id from `workflow_history` where doc_Type = '$doc_type' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = '$doc_type' and status in('Pending', 'Verified','Prepared') ) DS1 ON sma_ipc.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared') and status = 'Draft' and sma_comp_id in ( $comid ) and draft_by = '$user' ";
								}
			
								
								if($user == 'Admin' ){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_ipc` where approval_status in('Pending', 'Verified','Prepared')  ";
								}
								
								$sql .= " and del !='Y' ";
			
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts  = $r1['approval_status'];
									$pcnt_ip = $r1['cnt'];
								}
						
//Payment Pending
							$doc_type	= 'PY';
							$pcnt_py = 0;
					
								if ( $role == 'Checker - Account' || $role =='Accountant' || $role == 'Checker' ){
									$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con, $sql);
									while( $rs = mysqli_fetch_array($qry) ){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
								}	
									
								if ($role =='HOD - Account' || $role =='Project Manager'){
									$id_var1 = '';
									$sql = " SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' ";
									//echo $sql. "<BR>";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var1 .= $rs['doc_id'].',';
									}
									$id_var1 .= '0';
									
									$id_var2 = '';
									$sql = " SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' ";
									//echo $sql. "<BR>";
									$qry = mysqli_query($con,$sql);
									while ( $rs = mysqli_fetch_array($qry)){
										$id_var2 .= $rs['doc_id'].',';
									}
									$id_var2 .= '0';
									$sql = " SELECT approval_status, count(*) as cnt from payment_header where approval_status in ( 'Pending', 'Verified', 'Prepared', 'Submited' ) and ( id in ( $id_var1 ) or id in ( $id_var2 ) ) and del!='Y' ";
									
								}
								else if ($role =='Checker - Account' || $role =='Accountant'){
									//$sql="SELECT approval_status, count(*) as cnt from payment_header where approval_status in('Pending', 'Verified','Prepared') and company_id in ($comid) and draft_by = '$user' order by id desc";
									$sql = "SELECT approval_status, count(*) as cnt from payment_header DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared','Submited') 
											and id in ( $id_var ) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared','Submited')  and DS.del!='Y' ";
							//echo $sql;
							
								}
								else if ( $role == 'Checker'){
									
									$sql = "SELECT approval_status, count(*) as cnt from payment_header DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared','Submited') 
											and id in ( $id_var ) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared','Submited') and DS.del!='Y' ";
								//	echo $sql;
								}
								else if ( $role =='Maker'){
									
									$sql = "SELECT approval_status, count(*) as cnt  FROM `payment_header` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
											select max(id) as id from `workflow_history` where doc_Type = '$doc_type' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = '$doc_type' and status in('Pending', 'Verified','Prepared','Submited') ) DS1 ON payment_header.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared','Submited') and status = 'Draft' and company_id in ( $comid ) and draft_by = '$user'  and del!='Y' ";
									//echo $sql;
								}
								else {
									$sql="SELECT approval_status, count(*) as cnt from payment_header where draft_by = '$user'  and del!='Y' ";
								}

							//	$sql .= " and del !='Y' ";
//echo $role. "<BR>";
//echo $sql. "<BR>";								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt_py = $r1['cnt'];
								}
				//echo $pcnt_py. " <<>>";				

//Payment End

// Petty Cash Start

//Pending
							$doc_type	= 'PC';
							
					//echo $role. "<BR>";
								if ( $role == 'Checker - Account' || $role =='Accountant' || $role == 'Checker' || $role == 'Project Manager' || $role == 'HOD - Account' ){
									$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con, $sql);
									while( $rs = mysqli_fetch_array($qry) ){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
								}
								
							$pcnt_pc = 0;
							$id_var1 = '0';
							$id_var2 = '0';
					//echo $role. "<BR>";
								if ( $role == 'Checker - Account' || $role =='Accountant' || $role == 'Checker' || $role == 'Project Manager' || $role == 'HOD - Account' || $role == 'Project Incharge' ){
									$id_var1 = '';
									$sql = " SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' ";
									//echo $sql. "<BR>";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var1 .= $rs['doc_id'].',';
									}
									$id_var1 .= '0';
									
									/* $id_var2 = '';
									$sql = " SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' ";
									//echo $sql. "<BR>";
									$qry = mysqli_query($con,$sql);
									while ( $rs = mysqli_fetch_array($qry)){
										$id_var2 .= $rs['doc_id'].',';
									} */
									$id_var2 .= '0';
									$sql = " SELECT approval_status, count(*) as cnt from sma_pettycash where approval_status in ( 'Pending', 'Verified', 'Prepared', 'Submited' ) and ( id in ( $id_var1 ) or id in ( $id_var2 ) ) and del!='Y' ";
									
								}
								
								if($user=='Admin' ){
									$sql = " SELECT approval_status, count(*) as cnt from sma_pettycash where approval_status in ( 'Pending', 'Verified', 'Prepared', 'Submited' )  and del!='Y' ";
								}
								
							
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt_pc = $r1['cnt'];
								}
								
							$doc_type	= 'PC';
							
					//echo $role. "<BR>";
//echo $sql."<BR>";	

// Petty Cash End				
			
//TRavel Request Pending								
						$doc_type	= 'TA';
						$pcnt_ta = 0;
						$cnt_my		= 0;
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_traval_approval where emp_id = '$usrid' and status != 'Withdraw' and del!='Y'  ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  	= mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
//My Pending					
								$sql='';			
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_traval_approval where emp_id = '$usrid' and approval_status in('Pending', 'Prepared') and status != 'Withdraw' and id < 1 ";  // Don't show pending list for other users.
								}
								
								//SELECT * from sma_traval_approval where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TA'  and reviewed_by = '100' ) and approval_status = 'Prepared'

								if($department=='12' || $role=='accountant' || $role == 'CXO' || $role == 'Project Manager' ){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_traval_approval where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TA' and status in ('Pending') and reviewed_by = '$usrid' ) and approval_status in ( 'Pending') ";
									
								//SELECT * from sma_traval_approval where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TA' and status in ('Pending') and reviewed_by = '29' ) and approval_status in( 'Pending') and del !='Y' order by id desc
								
								}
								
								if( $role == 'Maker'  || $role == 'Cheker' ){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_traval_approval where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TA' and status in ('Pending', 'Prepared') and reviewed_by = '$usrid' ) and approval_status in ( 'Pending', 'Prepared') ";
									
								}

								if ($user=='Admin'  ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_traval_approval where approval_status in('Pending', 'Prepared') ";
									
								}	
								

							if(!empty($sql)){
								//$sql .= " and status != 'Withdraw' ";	
								$sql .= " and del !='Y' ";

								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt_ta = $r1['cnt'];
								}
							}
						//echo $sql. ' ' . $pcnt_ta . ' ' . $role;			
		
								if ($cnt_my>0){
								//	$pcnt_ta =  0 ;
								}
					//echo $sql. ' ' . $pcnt_ta . ' ' . $role;			
//echo $sql. $department. ' ' .$role." <<<>>><BR>";
//Travel Expenses Pending	
						$doc_type	= 'TE';
						$pcnt_te = 0;
								$pcnt = 0;
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and status != 'Withdraw'  and del!='Y'  ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
							
								//if ($cnt_my>0){
							//		$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and approval_status in('Pending') and status != 'Withdraw' ";
							//	}
								//else 
								if($department=='12' || $department=='13' || $department=='14' || $role == 'CXO' ){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TE' and status in ('Pending') and reviewed_by = '$usrid' ) and approval_status in('Pending') and exp_type = 'T' and status != 'Withdraw' ";
									
								}
								else {
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TE' and status in ('Pending') and reviewed_by = '$usrid' ) and approval_status in('Pending') and exp_type = 'T' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where approval_status in('Pending') and exp_type = 'T' and status != 'Withdraw' ";
									
								}	
								
								$sql .= " and del !='Y' ";
//echo $sql;	
								$pcnt_te = 0;
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt_te = $r1['cnt'];
								}

//Regular Expense MY Pending
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and status != 'Withdraw'  and del!='Y'  ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];

								//if ($cnt_my>0){
								//	$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and approval_status in('Pending') and status != 'Withdraw' ";
								//}
								//else 
								if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where exp_type = 'R' and id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'RE' and status in ('Pending') and approval_status in('Pending') and reviewed_by = '$usrid' ) and status != 'Withdraw' ";
								}
								else {
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where exp_type = 'R' and id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'RE' and status in ('Pending') and reviewed_by = '$usrid' ) and approval_status in('Pending') and  status != 'Withdraw' ";
									
								}

								if ($user=='Admin'){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where exp_type = 'R' and  approval_status in('Pending') and status != 'Withdraw' ";
									
								}
								
								$sql .= " and del !='Y' ";
								
						//echo $sql. ' ' .$department;
								$pcnt_tr = 0;
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt_tr = $r1['cnt'];
								}
								
//Operating Expense	Pending
						$doc_type	= 'CE';
						$pcnt_ar = 0;
								$pcnt = 0;
								$department = $_SESSION['department'];
								
								//$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'C' and status != 'Withdraw' ";
								//$result = mysqli_query($con, $sql);
								//echo mysqli_error($con);
								//$r1  = mysqli_fetch_array($result);
								//$cnt_my = $r1['cnt'];
							
								//if ($cnt_my>0){
								//	$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'C' and approval_status in('Pending') and status != 'Withdraw' ";
								//}
								//else 
								if( $department=='12' || $department=='13' || $department=='14' ){

									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'CE' and status in ('Pending') and reviewed_by = '$usrid' ) and exp_type = 'C' and status != 'Withdraw' and approval_status = 'Pending' ";

								}
								else {
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'CE' and status in ('Pending') and reviewed_by = '$usrid' ) and exp_type = 'C' and status != 'Withdraw' and approval_status = 'Pending' ";
									
								}
								
								if ( $user=='Admin' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where approval_status in('Pending') and exp_type = 'C' and status != 'Withdraw' ";
									
								}
						
								$sql .= " and del !='Y' ";
								
						//echo $sql;	
								$pcnt_ce = 0;
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt_ce = $r1['cnt'];
								}
								
?>		

	<div class="box-body">
		<form class="form-horizontal" >
		<div class="form-group">	
                     <!--class="btn btn-lg btn-warning" -->
			<?php $i = $menu_id[2]; if ( $dashboard[$i] !='Y' ){ ?>
			<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary" data-toggle="tab" style="color:white;" onclick="getpendingAP()" >Approval Memo<br><?php echo $pcnt_ap; ?></a>
			</div>
			<?php } ?>	
			<?php $i = $menu_id[3]; if ( $dashboard[$i] !='Y' ){ ?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#FF3383;color:white;" data-toggle="tab" onclick="getpendingPO()" >Purchase Order<br><?php echo $pcnt_po; ?></a>
				</div>
			<?php } ?>	
			<?php
			/* $i = $menu_id[4]; if ( $dashboard[$i] !='Y' ){ 
				$spac = '';
				for($i=0;$i<5;$i++){$spac.='&nbsp;';}
			?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#A1D521;color:white;" data-toggle="tab" onclick="getpendingGS()" ><?php echo $spac ?> GRN /SRN <?php echo $spac ?><br><?php echo $pcnt_gs; ?></a>
				</div>
			<?php }
			*/
			?>	
			<?php $i = $menu_id[5]; if ( $dashboard[$i] !='Y' ){ ?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#FC073F;color:white;" data-toggle="tab" onclick="getpendingSI()" >Supplier Invoice<br><?php echo $pcnt_si; ?></a>
				</div>
			<?php } ?>	
			
			<?php $i = $menu_id[30]; if ( $dashboard[$i] !='Y' ){ ?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#F9A406;color:white;" data-toggle="tab" onclick="getpendingCE()" >Operating Expense<br><?php echo $pcnt_ce; ?></a>
				</div>
			<?php } ?>	
			
			
			<?php $i = $menu_id[7]; if ( $dashboard[$i] !='Y' ){ 
				$spac = '';
				for($i=0;$i<11;$i++){$spac.='&nbsp;';}
			?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#3FA0A2;color:white;" data-toggle="tab" onclick="getpendingIP()" ><?php echo $spac ?> I P C <?php echo $spac ?><br><?php echo $pcnt_ip; ?></a>
				</div>
			<?php } ?>	
			
			<?php $i = $menu_id[6]; if ( $dashboard[$i] !='Y' ){ 
				$spac = '';
				for($i=0;$i<6;$i++){$spac.='&nbsp;';}
			?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#D8C80D;color:white;" data-toggle="tab" onclick="getpendingPY()" ><?php echo $spac ?> Payment <?php echo $spac ?><br><?php echo $pcnt_py; ?></a>
				</div>
			<?php } ?>	
			
			
			
		</div>
		
		<div class="form-group">
			
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				    <a href="#" class="btn btn-lg btn-primary123" style="background-color:orange;color:white;" data-toggle="tab" onclick="getpendingPC()" >&nbsp;&nbsp;&nbsp;&nbsp; Petty Cash &nbsp;&nbsp;&nbsp;&nbsp;<br><?php echo '&nbsp;&nbsp;'.$pcnt_pc. '&nbsp;&nbsp;' ?></a>
				</div>
			
			<?php $i = $menu_id[8]; if ( $dashboard[$i] !='Y' ){ 
				$spac = '';
				for($i=0;$i<1;$i++){$spac.='&nbsp;';}
			?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#A662EE;color:white;" data-toggle="tab" onclick="getpendingTR()" ><?php echo $spac ?>Travel Request <?php echo $spac ?><br><?php echo $pcnt_ta; ?></a>
				</div>
			<?php } ?>	
			<?php $i = $menu_id[9]; if ( $dashboard[$i] !='Y' ){ ?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#54BE41;color:white;" data-toggle="tab" onclick="getpendingTE()" >Travel Expenses<br><?php echo $pcnt_te; ?></a>
				</div>
			<?php } ?>	
			<?php $i = $menu_id[30]; if ( $dashboard[$i] !='Y' ){ ?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:blue;color:white;" data-toggle="tab" onclick="getpendingRE()" >Regular Expense<br><?php echo $pcnt_tr; ?></a>
				</div>
			<?php } ?>	
			
			
			
			<?php $i = $menu_id[30]; if ( $dashboard[$i] !='Y' ){
					$userid   	    = $_SESSION['usrid'];
				$sql = "select count(*) as cnt from workflow_history a INNER JOIN 
					(SELECT max(id) as id FROM `workflow_history` where doc_type= 'IN' and (reviewed_by = '$userid' || create_by = '$userid') group by  doc_id ) as DS
					ON a.id = DS.id and ( status  in( 'Received','Sent', 'Draft'  ) ) and doc_type = 'IN' "; 
				$res  = mysqli_query($con, $sql);
				echo mysqli_error($con);		
				$r1   = mysqli_fetch_array($res);
				$icnt = $r1['cnt'];
			?>
<!--				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				
				    <a href="<?php echo $baseurl . 'dms/inbox_scr.php?sub=list'; ?>" class="btn btn-lg btn-primary123" style="background-color:green;color:white;" >&nbsp;&nbsp; Inward &nbsp;&nbsp;<br><?php echo $icnt; ?></a>
				</div>
-->				
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				
				    <a href="<?php echo $baseurl . 'dashbmob.php?sub=list'; ?>" class="btn btn-lg btn-primary123" style="background-color:green;color:white;" >DMS Dashboard<br>&nbsp;</a>
				</div>
				
			<?php } ?>	
			
			<?php 
				/* $sql = "SELECT count(*) as tskcnt, a.status FROM task_workflow a, payment_header b 
							where b.id = a.payment_id and b.company_id in ($comid)
						and a.status = 'P' and a.id in 
							( SELECT max(a.id) FROM `task_workflow` a, payment_header b 
								where a.payment_id = b.id and b.company_id in ($comid) group by doc_id, create_by ) 
						group by a.status "; */
				$sql = "select count(*)  as tskcnt from sma_pending_task a, payment_header b where status_task ='P' and a.payment_id = b.id and b.company_id in ($comid) ";	
//echo $sql;						
				$res  = mysqli_query($con, $sql);
				echo mysqli_error($con);		
				$r1   = mysqli_fetch_array($res);
				$p_tskcnt = $r1['tskcnt'];
			?>	
			
			<!--<div class="col-sm-4" style="float:left; margin-top: 10px; ">
				<a href="#" class="btn btn-lg btn-primary123" data-toggle="tab" >&nbsp;<br><?php echo '&nbsp;' ?></a>
			</div>-->
			
		</div>
		</form>
			
			<div class="col-sm-5" style="float:left; margin-top: 10px; ">
				<span class='mypendg' style="font-size:26px;text-align:left;background-color:grey;color:white;">My Pending-Approval Memo</span>
			</div>
		
		
		</div>
	
<?php		
	
        //echo $value;
    }
	
//All Pending	
if(isset($_POST['sub2'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
						
						$doc_type	= 'AP';
						$allpcnt_ap = 0;
								
								$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
									
								$allcnt=0;
								if ($role =='Maker' ){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where project in ($comid) and draft_by = '$user' and status not in ('Completed', 'Draft')  and del!='Y'  ";
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt from sma_approval_memo DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
										and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') and DS.status != 'Draft'  and DS.del!='Y'  ";	
								
								}
								
								if($user == 'Admin' ){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where status not in ('Completed', 'Draft')  and del!='Y' ";
							//echo $sql;
								}
								
								
//echo $sql;
						
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt_ap = $r1['cnt'];
								}
							
						$doc_type	= 'PO';
						$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
									
						$pcnt_po = 0;
								if ($role =='Maker'){
								$sql = "SELECT approval_status, count(*) as cnt FROM `sma_purchase_order` where project in ($comid) and status not in('Completed', 'Draft') and draft_by = '$user'  and del!='Y'  ";
							}
							else {
								$sql = "SELECT approval_status, count(*) as cnt from sma_purchase_order DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
										and id in ($id_var) ) DS1 
										ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared')  and DS.del!='Y' ";	
		//echo $sql;
		
							}
							
							if($user == 'Admin' || $role == 'CXO' ){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_purchase_order` where status not in('Completed', 'Draft')  and del!='Y'  ";
							}
						
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt_po = $r1['cnt'];
								}
						
						
						$doc_type	= 'GS';
						$id_var = '';
						/*			$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
						*/
						$id_var .= '0';
						$pcnt_gs = 0;
			
							if ($role =='Maker'){
								$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where status not in('Completed', 'Draft') and draft_by = '$user'  and del!='Y'  ";
							}
							else {
								//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where status not in('Completed', 'Draft') ";
								$sql = "SELECT approval_status, count(*) as cnt from sma_grn_srn DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
										and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Prepared')  and DS.del!='Y'  ";
							}
							
								if($user == 'Admin'  || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where status not in('Completed', 'Draft')  and del!='Y' ";
								}
							
//echo $sql."<BR>";
								//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where approval_status in('Pending', 'Verified')  ";
						/*		$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt_gs = $r1['cnt'];
								}
						*/
						$doc_type	= 'SI';
						$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
						$pcnt_gs = 0;
						
						$pcnt_si = 0;						
							if ($role =='Maker'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_supplier_invoice` where company_id in ( $comid ) and status not in('Completed', 'Draft') and draft_by = '$user'  and del!='Y' ";
								}
							else {
									$sql = "SELECT approval_status, count(*) as cnt from sma_supplier_invoice DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
										and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where company_id in ( $comid ) and DS.approval_status in('Pending', 'Verified','Prepared')  and DS.del!='Y'  ";
								}
									
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_supplier_invoice` where status not in('Completed', 'Draft')  and del!='Y'  ";
								}
														
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt_si = $r1['cnt'];
								}
						
								
								$doc_type	= 'IP';
								$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
									
								$pcnt_ip = 0;
								if ($role =='Maker'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_ipc` where sma_comp_id in ($comid) and status not in('Completed', 'Draft') and draft_by = '$user'  and del!='Y'  ";
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt from sma_ipc DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
										and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.sma_comp_id in ( $comid ) and DS.approval_status in('Pending', 'Verified','Prepared')  and DS.del!='Y'  ";	
								}
								
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_ipc` where status not in('Completed', 'Draft')  and del!='Y'  ";
								}
								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts  = $r1['approval_status'];
									$allpcnt_ip = $r1['cnt'];
								}
						
							$doc_type	= 'PY';
							$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
									
							$pcnt_py = 0;
								
								if ($role =='Maker'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `payment_header` where company_id in ($comid) and status not in('Completed', 'Draft') and draft_by = '$user'  and del!='Y'  ";
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt from payment_header DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
										and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in ( $comid ) and DS.approval_status in('Pending', 'Verified','Prepared')  and DS.del!='Y' ";		
								//echo $sql;			
								
								}	
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `payment_header` where status not in('Completed', 'Draft')  and del!='Y' ";
								}
								
								//$sql .= " and del !='Y' ";
								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt_py = $r1['cnt'];
								}
//Payment All Pending End.

//Petty Cash								
//Pending All					
							$doc_type ='PC';
							$pallcnt_pc = 0;
							$id_var1 = '0';
							$id_var2 = '0';
							$var_null ='';
					//echo $role. "<BR>";
								if ( $role == 'Checker - Account' || $role =='Accountant' || $role == 'Checker' || $role == 'Project Manager' || $role == 'HOD - Account' ){
									$id_var1 = '';
									$sql = " SELECT max(doc_id) as doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' ";
					//echo $sql. "<BR>";
									$qry = mysqli_query($con,$sql);
									//echo $rowaffected = mysqli_affected_rows($con);
									while($rs = mysqli_fetch_array($qry)){
										$id_var1 .= $rs['doc_id'].',';
										$var_null .= $rs['doc_id'];
									}
									if(empty($var_null)){
										$id_var1 = '0';
									}
									else {
										$id_var1 .= '0';
									}
									
									/* $id_var2 = '';
									$sql = " SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' ";
									//echo $sql. "<BR>";
									$qry = mysqli_query($con,$sql);
									while ( $rs = mysqli_fetch_array($qry)){
										$id_var2 .= $rs['doc_id'].',';
									} */
									//$id_var2 .= '0';
									$sql = " SELECT approval_status, count(*) as cnt from sma_pettycash where approval_status in ( 'Pending', 'Verified', 'Prepared', 'Submited' ) and ( id in ( $id_var1 ) or id in ( $id_var2 ) ) and del!='Y' ";
									
								}
								
								if($user=='Admin' ){
									$sql = " SELECT approval_status, count(*) as cnt from sma_pettycash where approval_status in ( 'Pending', 'Verified', 'Prepared', 'Submited' )  and del!='Y' ";
								}
								
						//echo $sql."<BR>";		
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pallcnt_pc = $r1['cnt'];
								}
								
//All Pending End
//Petty Cash

						$doc_type	= 'TA';
						$pcnt_ar = 0;
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_traval_approval where emp_id = '$usrid' and status != 'Withdraw' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
//All Pending								

								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_traval_approval where emp_id = '$usrid' and approval_status in('Prepared') and status != 'Withdraw' ";
								}
								
								if($department=='12' || $role=='accountant' || $role =='CXO' || $role == 'Project Manager'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_traval_approval where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TA' and status in ('Prepared') and create_by = '$usrid' ) and approval_status = 'Prepared' ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_traval_approval where approval_status in('Prepared') and status != 'Withdraw' ";
									
								}
								
								$sql .= " and del !='Y' ";
//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt_ar = $r1['cnt'];
								}
								
							$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and status != 'Withdraw'  and del!='Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];	
							$doc_type	= 'TE';
								$allpcnt_te = 0;
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and approval_status in ('Prepared', 'Pending' ) and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14' || $role == 'CXO'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TE' and status = 'Prepared' and create_by = '$usrid' ) and approval_status = 'Prepared' and exp_type = 'T' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'T' and approval_status in ('Prepared') ";
									
								}
								
								$sql .= " and del !='Y' ";
								
							//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt_te = $r1['cnt'];
								}
								
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and status != 'Withdraw'  and del!='Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];	
							$doc_type	= 'RE';	
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and approval_status in('Pending') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14' || $role == 'CXO'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'RE' and status = 'Prepared' and create_by = '$usrid' ) and approval_status = 'Prepared' and exp_type = 'R' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'R' and approval_status in ('Prepared', 'Pending' ) ";
									
								}
								
								$sql .= " and del !='Y' ";
								
							//echo $sql;

								$allpcnt_re =0;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt_re = $r1['cnt'];
								}
							
//+++++++++++++Company Expenses all Pending
							$sql = "SELECT count(*) as cnt from sma_travel_expenses where draft_by = '$user' and exp_type = 'C' and status != 'Withdraw'  and del!='Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];	
							$doc_type	= 'CE';	
								$allpcnt_ce = 0;
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where draft_by = '$user' and exp_type = 'C' and approval_status in ('Prepared', 'Pending' ) and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14' || $role == 'CXO'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'CE' and status = 'Prepared' and create_by = '$usrid' ) and approval_status = 'Prepared' and exp_type = 'C' and status != 'Withdraw' ";
									
								}
								else {
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'CE' and status = 'Prepared' and create_by = '$usrid' ) and approval_status = 'Prepared' and exp_type = 'C' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'C' and approval_status in ('Prepared', 'Pending' ) ";
									
								}
								
								$sql .= " and del !='Y' ";
								
				//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt_ce = $r1['cnt'];
								}
								
?>		

	<div class="box-body">
		<form class="form-horizontal" >	
		<div class="form-group">	
                     <!--class="btn btn-lg btn-warning" -->
			<?php $i = $menu_id[2]; if ( $dashboard[$i] !='Y' ){ ?>
			<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary" data-toggle="tab" style="color:white;" onclick="getallpendingAP()" >Approval Memo<br><?php echo $allpcnt_ap; ?></a>
			</div>
			<?php } ?>	
			<?php $i = $menu_id[3]; if ( $dashboard[$i] !='Y' ){ ?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#FF3383;color:white;" data-toggle="tab" onclick="getallpendingPO()" >Purchase Order<br><?php echo $allpcnt_po; ?></a>
				</div>
			<?php } ?>	
			<?php 
			/*$i = $menu_id[4]; if ( $dashboard[$i] !='Y' ){ 
				$spac = '';
				for($i=0;$i<5;$i++){$spac.='&nbsp;';}
			?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#A1D521;color:white;" data-toggle="tab" onclick="getallpendingGS()" ><?php echo $spac ?> GRN /SRN <?php echo $spac ?><br><?php echo $allpcnt_gs; ?></a>
				</div>
			<?php }
			*/		
			?>	
			<?php $i = $menu_id[5]; if ( $dashboard[$i] !='Y' ){ ?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#FC073F;color:white;" data-toggle="tab" onclick="getallpendingSI()" >Supplier Invoice<br><?php echo $allpcnt_si; ?></a>
				</div>
			<?php } ?>	
			
			<?php $i = $menu_id[30]; if ( $dashboard[$i] !='Y' ){ ?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#F9A406;color:white;" data-toggle="tab" onclick="getallpendingCE()" >Operating Expense<br><?php echo $allpcnt_ce; ?></a>
				</div>
			<?php } ?>	
			
			<?php $i = $menu_id[7]; if ( $dashboard[$i] !='Y' ){ 
				$spac = '';
				for($i=0;$i<11;$i++){$spac.='&nbsp;';}
			?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#3FA0A2;color:white;" data-toggle="tab" onclick="getallpendingIP()" ><?php echo $spac ?> I P C <?php echo $spac ?><br><?php echo $allpcnt_ip; ?></a>
				</div>
			<?php } ?>	
			
			<?php $i = $menu_id[6]; if ( $dashboard[$i] !='Y' ){ 
				$spac = '';
				for($i=0;$i<6;$i++){$spac.='&nbsp;';}
			?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#D8C80D;color:white;" data-toggle="tab" onclick="getallpendingPY()" ><?php echo $spac ?> Payment <?php echo $spac ?><br><?php echo $allpcnt_py; ?></a>
				</div>
			<?php } ?>	
		</div>
		
		<div class="form-group">
			
			<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				    <a href="#" class="btn btn-lg btn-primary123" style="background-color:orange;color:white;" data-toggle="tab" onclick="getallpendingPC()" >&nbsp;&nbsp;&nbsp;&nbsp; Petty Cash &nbsp;&nbsp;&nbsp;&nbsp;<br><?php echo '&nbsp;&nbsp;'.$pallcnt_pc. '&nbsp;&nbsp;' ?></a>
			</div>
				
			<?php $i = $menu_id[8]; if ( $dashboard[$i] !='Y' ){ 
				$spac = '';
				for($i=0;$i<1;$i++){$spac.='&nbsp;';}
			?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#A662EE;color:white;" data-toggle="tab" onclick="getallpendingTR()" ><?php echo $spac ?>Travel Request <?php echo $spac ?><br><?php echo $allpcnt_ar; ?></a>
				</div>
			<?php } ?>	
			<?php $i = $menu_id[9]; if ( $dashboard[$i] !='Y' ){ ?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#54BE41;color:white;" data-toggle="tab" onclick="getallpendingTE()" >Travel Expenses<br><?php echo $allpcnt_te; ?></a>
				</div>
			<?php } ?>	
			<?php $i = $menu_id[30]; if ( $dashboard[$i] !='Y' ){ ?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:blue;color:white;" data-toggle="tab" onclick="getallpendingRE()" >Regular Expense<br><?php echo $allpcnt_re; ?></a>
				</div>
			<?php } ?>	
			
			
			<div class="col-sm-4" style="float:left; margin-top: 10px; ">
				<a href="#" class="btn btn-lg btn-primary123" data-toggle="tab" >&nbsp;<br><?php echo '&nbsp;' ?></a>
			</div>
			
		</div>
		</form>
		
			<div class="col-sm-5" style="float:left; margin-top: 10px; ">
				<span class='mypendg'  style="font-size:26px;text-align:left;background-color:grey;color:white;">All Pending</span>
			</div>
			
		</div>
			
		
<?php		
	
        //echo $value;
    }
	

	
//Approved	
if(isset($_POST['sub3'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
						
						$doc_type	= 'AP';
							$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
									
								$ap_acnt = 0;
								if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account' || $role == 'HOD'){
									$sql = "SELECT approval_status, count(*) as cnt from sma_approval_memo DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Approved')  and del!='Y' ";		
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where project in ($comid) and approval_status = 'Approved' and draft_by = '$user'  and del!='Y' ";
								}
								
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where approval_status = 'Approved'  and del!='Y' ";
								}	
						
								//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where approval_status = 'Approved'  and draft_by = '$user'  ";
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$ap_acnt = $r1['cnt'];
								}
							
							$doc_type	= 'PO';
							
							$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
									
							$po_acnt = 0;
								if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account' || $role == 'HOD'  ){
									$sql = "SELECT approval_status, count(*) as cnt from sma_purchase_order DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PO' and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Approved')  and DS.del!='Y' "	;
										
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_purchase_order` where project in ($comid) and approval_status = 'Approved'  and draft_by = '$user'  and del!='Y' ";
								}
								
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_purchase_order` where approval_status = 'Approved'  and del!='Y' ";
								}	
								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$po_acnt = $r1['cnt'];
								}
						
						$doc_type	= 'GS';
						$id_var = '';
						/*			$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
						*/
						$id_var .= '0';
						$gs_acnt = 0;
			
								if ($role =='Maker'){
								$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where approval_status = 'Approved' and draft_by = '$user'  and del!='Y' ";
							}
							else {
								//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where status not in('Completed', 'Draft') ";
								$sql = "SELECT approval_status, count(*) as cnt from sma_grn_srn DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and approval_status = 'Approved' 
										and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.approval_status = 'Approved'  and DS.del!='Y'  ";
							}
							
								if($user == 'Admin'  || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where approval_status = 'Approved'  and del!='Y' ";
								}
								
//echo $sql."<BR>";
								//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where approval_status in('Pending', 'Verified')  ";
						/*		$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$gs_acnt = $r1['cnt'];
								}
						*/		

//Suuplier Invoice						
						$doc_type	= 'SI';
						$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
						$si_acnt = 0;						
								if ($role =='Checker' || $role =='Accountant'){
									$sql = "SELECT approval_status, count(*) as cnt from sma_supplier_invoice DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Approved')  and DS.del!='Y' ";
								}
								else if ( $role =='Checker - Account' || $role =='HOD - Account' || $role =='Maker' ){
									$sql = "SELECT approval_status, count(*) as cnt from sma_supplier_invoice where company_id in ( $comid ) and  approval_status in('Approved') and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = '$doc_type') 
									or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type') or draft_by = '$user' ) and del!='Y' ";
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_supplier_invoice` where company_id in ($comid) and approval_status = 'Approved' and draft_by = '$user'  and del!='Y' ";
								}
								
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_supplier_invoice` where  approval_status = 'Approved'  and del!='Y' ";
								}
								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$si_acnt = $r1['cnt'];
								}
						
//IPC								
								$doc_type	= 'IP';
								$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
								$ip_acnt = 0;
								if ($role =='Accountant' || $role =='Maker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account' || $role == 'HOD' ){
									//$sql="SELECT approval_status, count(*) as cnt from sma_ipc where sma_comp_id in ( $comid ) and  approval_status in('Approved') 
									//	and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = '$doc_type') 
									//	or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type') or draft_by = '$user' )   ";
									$sql = "SELECT approval_status, count(*) as cnt from sma_ipc DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.sma_comp_id in (  $comid ) and DS.approval_status in('Approved') and DS.del !='Y'";	
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_ipc` where sma_comp_id in ($comid) and approval_status = 'Approved' and draft_by = '$user' and del !='Y'";
								}
								
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_ipc` where  approval_status = 'Approved' and del !='Y' ";
								}	
								
								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts  = $r1['approval_status'];
									$ip_acnt = $r1['cnt'];
								}

//Payment							
							$doc_type	= 'PY';
							$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
							$py_acnt = 0;
								
								if ($role =='HOD - Account' || $role =='Project Manager' ){
									$id_var1 = '';
									$sql = " SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' ";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var1 .= $rs['doc_id'].',';
									}
									$id_var1 .= '0';	
									$sql = " SELECT approval_status, count(*) as cnt from payment_header where (id in ( $id_var1 ) or id in ( $id_var1 )) and  approval_status in('Approved') or draft_by = '$user' order by id desc ";
								}
								else if ($role =='Checker - Account' || $role =='Accountant'){
										$id_var1 = '';
									$sql = " SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' ";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var1 .= $rs['doc_id'].',';
									}
									$id_var1 .= '0';
									
									$id_var2 = '';
									$sql = " SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' ";
									$qry = mysqli_query($con,$sql);
									while( $rs = mysqli_fetch_array($qry) ){
										$id_var2 .= $rs['doc_id'].',';
									}
									$id_var2 .= '0';
									$sql = " SELECT approval_status, count(*) as cnt from payment_header where ( id in ( $id_var2 ) or id in ( $id_var1 ) ) and approval_status in('Approved') or draft_by = '$user' order by id desc";
								}
								else if ( $role == 'Checker'){
									//$sql="SELECT approval_status, count(*) as cnt from payment_header where status = 'Completed' and company_id in ($comid) and  approval_status in('Approved') order by id desc";
									$sql = "SELECT approval_status, count(*) as cnt from payment_header DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in ( 'Approved' ) 
											and id in ( $id_var) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in ( $comid ) and DS.approval_status in('Approved') and DS.del !='Y' ";
								//	echo $sql;
								}
								else if ( $role =='Maker'){
									$sql = "SELECT approval_status, count(*) as cnt from payment_header where status = 'Completed' and company_id in ($comid) and id in (select payment_hdr_id from payment_details where supp_id in ( select id from sma_supplier_invoice where draft_by = '$user' )) and approval_status in('Approved') and del !='Y' order by id desc";
									//echo $sql;
								}
								else {
									$sql="SELECT approval_status, count(*) as cnt from payment_header where draft_by = '$user' and  approval_status in('Approved') and del !='Y' order by id desc";
								}
								
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `payment_header` where  approval_status = 'Approved' and del !='Y' ";
								}	
								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$py_acnt = $r1['cnt'];
								}

// Travel Approval								
						$doc_type	= 'TA';
						$ta_acnt = 0;
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_traval_approval where emp_id = '$usrid' and status != 'Withdraw' and del !='Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
//Approved								

								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_traval_approval where emp_id = '$usrid' and approval_status in('Booked') and status != 'Withdraw' ";
								}
								
								if($department=='12' || $department=='13' || $department=='14' || $role == 'Project Manager'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_traval_approval where status != 'Withdraw' and approval_status in('Booked') ";
									
								}

								if ($user=='Admin' || $role=='CXO' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_traval_approval where status != 'Withdraw' and approval_status in ('Booked') ";
									//echo $sql;	
								}

								$sql .= " and del !='Y' ";
								
							//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$ta_acnt = $r1['cnt'];
								}

// Travel Expenses								
						$doc_type	= 'TE';
						$te_acnt = 0;
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and status != 'Withdraw' and del !='Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
										
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and approval_status in('Approved') and status != 'Withdraw' ";
								}
								
								if($department=='12' || $department=='13' || $department=='14' || $role == 'Project Manager' ){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'T' and approval_status in('Approved') ";
									
								}

								if ($user=='Admin'|| $role=='CXO' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'T' and approval_status in ('Approved') ";
									
								}
								
								$sql .= " and del !='Y' ";
								
								//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$te_acnt = $r1['cnt'];
								}

// Regular Expense								
						$doc_type	= 'TE';
						$re_acnt = 0;
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and status != 'Withdraw' and del !='Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
										
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and approval_status in('Approved') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14' ){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'R' and approval_status in('Approved') ";
									
								}

								if ($user=='Admin' || $role=='CXO' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'R' and approval_status in ('Approved') ";
									
								}
								
								$sql .= " and del !='Y' ";
								
								//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$re_acnt = $r1['cnt'];
								}
								
								
//Operating Expense								
						$doc_type	= 'CE';
						$ce_acnt = 0;
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and status != 'Withdraw' and del !='Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
										
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'C' and approval_status in('Approved') and status != 'Withdraw' ";
								}
								
								if($department=='12' || $department=='13' || $department=='14' || $role == 'Project Manager'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'C' and approval_status in('Approved') ";
									
								}

								if ($user=='Admin' || $role=='CXO'){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'C' and approval_status in ('Approved') ";
									
								}
								
								$sql .= " and del !='Y' ";
								
								//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$ce_acnt = $r1['cnt'];
								}
								
								
//Petty Cash								
//Approved
							$doc_type ='PC';
							$pc_acnt = 0;
							$id_var1 = '0';
							$id_var2 = '0';
							$var_null ='';
					//echo $role. "<BR>";
								if ( $role == 'Checker - Account' || $role =='Accountant' || $role == 'Checker' || $role == 'Project Manager' || $role =='HOD - Account' ){
									$id_var1 = '';
									$sql = " SELECT max(doc_id) as doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' ";
					//echo $sql. "<BR>";
									$qry = mysqli_query($con,$sql);
									//echo $rowaffected = mysqli_affected_rows($con);
									while($rs = mysqli_fetch_array($qry)){
										$id_var1 .= $rs['doc_id'].',';
										$var_null .= $rs['doc_id'];
									}
									if(empty($var_null)){
										$id_var1 = '0';
									}
									else {
										$id_var1 .= '0';
									}
									
									 $id_var2 = '';
									 $var_null ='';
									$sql = " SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' ";
									//echo $sql. "<BR>";
									$qry = mysqli_query($con,$sql);
									while ( $rs = mysqli_fetch_array($qry)){
										$id_var2 .= $rs['doc_id'].',';
										$var_null .= $rs['doc_id'];
									}
									if(empty($var_null)){
										$id_var2 = '0';
									}
									else {
										$id_var2 .= '0';
									}
									$sql = " SELECT approval_status, count(*) as cnt from sma_pettycash where approval_status in ( 'Approved' ) and ( id in ( $id_var1 ) or id in ( $id_var2 ) || (draft_by = '$user') ) and del!='Y' ";
									
								}
								else {
									$sql = " SELECT approval_status, count(*) as cnt from sma_pettycash where draft_by = '$user' and approval_status in ( 'Approved' )  and del!='Y' ";
								}	
								
								if($user=='Admin' ){
									$sql = " SELECT approval_status, count(*) as cnt from sma_pettycash where approval_status in ( 'Approved' )  and del!='Y' ";
								}
								
						//echo $sql."<BR>";		
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pc_acnt = $r1['cnt'];
								}
								
//Approved End
//Petty Cash
			
?>		

	<div class="box-body">
		<form class="form-horizontal" >
		<div class="form-group">	
                     <!--class="btn btn-lg btn-warning" -->
			<?php $i = $menu_id[2]; if ( $dashboard[$i] !='Y' ){ ?>
			<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary" data-toggle="tab" style="color:white;" onclick="getapproveAP()" >Approval Memo<br><?php echo $ap_acnt; ?></a>
			</div>
			<?php } ?>	
			<?php $i = $menu_id[3]; if ( $dashboard[$i] !='Y' ){ ?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#FF3383;color:white;" data-toggle="tab" onclick="getapprovePO()" >Purchase Order<br><?php echo $po_acnt; ?></a>
				</div>
			<?php } ?>	
			<?php 
				/*$i = $menu_id[4]; if ( $dashboard[$i] !='Y' ){
				$spac = '';
				for($i=0;$i<5;$i++){$spac.='&nbsp;';}
			?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#A1D521;color:white;" data-toggle="tab" onclick="getapproveGS()" ><?php echo $spac ?> GRN /SRN <?php echo $spac ?><br><?php echo $gs_acnt; ?></a>
				</div>
			<?php } */
			?>	
			<?php $i = $menu_id[5]; if ( $dashboard[$i] !='Y' ){ ?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#FC073F;color:white;" data-toggle="tab" onclick="getapproveSI()" >Supplier Invoice<br><?php echo $si_acnt; ?></a>
				</div>
			<?php } ?>	
			
			<?php $i = $menu_id[30]; if ( $dashboard[$i] !='Y' ){ ?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#F9A406;color:white;" data-toggle="tab" onclick="getapproveCE()" >Operating Expense<br><?php echo $ce_acnt; ?></a>
				</div>
			<?php } ?>	
			
			
			<?php $i = $menu_id[7]; if ( $dashboard[$i] !='Y' ){ 
				$spac = '';
				for($i=0;$i<11;$i++){$spac.='&nbsp;';}
			?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#3FA0A2;color:white;" data-toggle="tab" onclick="getapproveIP()" ><?php echo $spac ?> I P C <?php echo $spac ?><br><?php echo $ip_acnt; ?></a>
				</div>
			<?php } ?>	
			
			<?php $i = $menu_id[6]; if ( $dashboard[$i] !='Y' ){ 
				$spac = '';
				for($i=0;$i<6;$i++){$spac.='&nbsp;';}
			?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#D8C80D;color:white;" data-toggle="tab" onclick="getapprovePY()" ><?php echo $spac ?> Payment <?php echo $spac ?><br><?php echo $py_acnt; ?></a>
				</div>
			<?php } ?>	
		</div>
		
		<div class="form-group">
			
			<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				    <a href="#" class="btn btn-lg btn-primary123" style="background-color:orange;color:white;" data-toggle="tab" onclick="getapprovePC()" >&nbsp;&nbsp;&nbsp;&nbsp; Petty Cash &nbsp;&nbsp;&nbsp;&nbsp;<br><?php echo '&nbsp;&nbsp;'.$pc_acnt. '&nbsp;&nbsp;' ?></a>
			</div>
			
			<?php $i = $menu_id[8]; if ( $dashboard[$i] !='Y' ){ 
				$spac = '';
				for($i=0;$i<1;$i++){$spac.='&nbsp;';}
			?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#A662EE;color:white;" data-toggle="tab" onclick="getapproveTR()" ><?php echo $spac ?>Travel Request <?php echo $spac ?><br><?php echo $ta_acnt; ?></a>
				</div>
			<?php } ?>	
			<?php $i = $menu_id[9]; if ( $dashboard[$i] !='Y' ){ ?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#54BE41;color:white;" data-toggle="tab" onclick="getapproveTE()" >Travel Expenses<br><?php echo $te_acnt; ?></a>
				</div>
			<?php } ?>	
			<?php $i = $menu_id[30]; if ( $dashboard[$i] !='Y' ){ ?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:blue;color:white;" data-toggle="tab" onclick="getapproveRE()" >Regular Expense<br><?php echo $re_acnt; ?></a>
				</div>
			<?php } ?>	
			
			
			<div class="col-sm-4" style="float:left; margin-top: 10px; ">
				<a href="#" class="btn btn-lg btn-primary123" data-toggle="tab" >&nbsp;<br><?php echo '&nbsp;' ?></a>
			</div>
			
		</div>
		</form>
		
			<div class="col-sm-5" style="float:left; margin-top: 10px; ">
				<span class='mypendg'  style="font-size:26px;text-align:left;background-color:grey;color:white;" >Approved</span>
			</div>
			
		</div>
			
		
<?php		
	
        //echo $value;
    }
	
	
//Rejected	
if(isset($_POST['sub4'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
						
						$doc_type	= 'AP';
							$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
								$ap_acnt = 0;
								if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account' || $role == 'HOD'){
									$sql = "SELECT approval_status, count(*) as cnt from sma_approval_memo DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Rejected') and DS.del !='Y' ";		
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where project in ($comid) and approval_status = 'Rejected' and draft_by = '$user' and del !='Y' ";
								}
								
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where approval_status = 'Rejected' and del !='Y' ";
								}	
						
								//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where approval_status = 'Rejected'  and draft_by = '$user'  ";
								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$ap_acnt = $r1['cnt'];
								}
							
							$doc_type	= 'PO';
							$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
									
							$po_acnt = 0;
								if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account' || $role == 'HOD'  ){
									$sql = "SELECT approval_status, count(*) as cnt from sma_purchase_order DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PO' and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Rejected') and DS.del !='Y' "	;
										
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_purchase_order` where project in ($comid) and approval_status = 'Rejected'  and draft_by = '$user' and del !='Y'  ";
								}
								
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_purchase_order` where approval_status = 'Rejected' and del !='Y' ";
								}	
								
								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$po_acnt = $r1['cnt'];
								}
						
						$doc_type	= 'GS';
						$id_var = '';
						/*			$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
						*/
						$id_var .= '0';
						$gs_acnt = 0;
			
								if ($role =='Maker'){
								$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where status not in('Completed', 'Draft') and draft_by = '$user' ";
							}
							else {
								//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where status not in('Completed', 'Draft') ";
								$sql = "SELECT approval_status, count(*) as cnt from sma_grn_srn DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
										and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Prepared') ";
							}
							
								if($user == 'Admin'  || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where status not in('Completed', 'Draft') ";
								}

								$sql .= " and del !='Y' ";
								
//echo $sql."<BR>";
							//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where approval_status in('Pending', 'Verified')  ";
							/*	$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$gs_acnt = $r1['cnt'];
								}
							*/	

//Suuplier Invoice						
						$doc_type	= 'SI';
						$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
						$si_acnt = 0;						
								if ($role =='Checker' || $role =='Accountant'){
									$sql = "SELECT approval_status, count(*) as cnt from sma_supplier_invoice DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Rejected') ";
								}
								else if ( $role =='Checker - Account' || $role =='HOD - Account' || $role =='Maker' ){
									$sql = "SELECT approval_status, count(*) as cnt from sma_supplier_invoice where company_id in ( $comid ) and  approval_status in('Rejected') and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = '$doc_type') 
									or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type') or draft_by = '$user' )";
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_supplier_invoice` where company_id in ($comid) and approval_status = 'Rejected' and draft_by = '$user' ";
								}
								
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_supplier_invoice` where  approval_status = 'Rejected' ";
								}
								
								$sql .= " and del !='Y' ";
								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$si_acnt = $r1['cnt'];
								}
						
//IPC								
								$doc_type	= 'IP';
								$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
								$ip_acnt = 0;
								if ($role =='Accountant' || $role =='Maker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account' || $role == 'HOD' ){
									//$sql="SELECT approval_status, count(*) as cnt from sma_ipc where sma_comp_id in ( $comid ) and  approval_status in('Rejected') 
									//	and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = '$doc_type') 
									//	or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type') or draft_by = '$user' )   ";
									$sql = "SELECT approval_status, count(*) as cnt from sma_ipc DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.sma_comp_id in (  $comid ) and DS.approval_status in('Rejected')";	
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_ipc` where sma_comp_id in ($comid) and approval_status = 'Rejected' and draft_by = '$user' ";
								}
								
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_ipc` where  approval_status = 'Rejected' ";
								}	
								
								$sql .= " and del !='Y' ";
			
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts  = $r1['approval_status'];
									$ip_acnt = $r1['cnt'];
								}

//Payment							
							$doc_type	= 'PY';
							$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
							$py_acnt = 0;
								
								if ($role =='HOD - Account' || $role =='Project Manager' ){
									$sql="SELECT approval_status, count(*) as cnt from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type')) and  approval_status in('Rejected') or draft_by = '$user' ";
								}
								else if ($role =='Checker - Account' || $role =='Accountant'){
									$sql="SELECT approval_status, count(*) as cnt from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type')) and  approval_status in('Rejected') or draft_by = '$user' ";
								}
								else if ( $role == 'Checker'){
									//$sql="SELECT approval_status, count(*) as cnt from payment_header where status = 'Completed' and company_id in ($comid) and  approval_status in('Rejected') order by id desc";
									$sql = "SELECT approval_status, count(*) as cnt from payment_header DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Rejected') 
											and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Rejected') ";
								//	echo $sql;
								}
								else if ( $role =='Maker'){
									$sql = "SELECT approval_status, count(*) as cnt from payment_header where status = 'Completed' and company_id in ($comid) and id in (select payment_hdr_id from payment_details where supp_id in ( select id from sma_supplier_invoice where draft_by = '$user' )) and approval_status in('Rejected') ";
									//echo $sql;
								}
								else {
									$sql="SELECT approval_status, count(*) as cnt from payment_header where draft_by = '$user' and  approval_status in('Rejected')";
								}
								
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `payment_header` where  approval_status = 'Rejected' ";
								}	
								
								$sql .= " and del !='Y' ";
				//echo $sql; 				
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$py_acnt = $r1['cnt'];
								}

// Travel Approval								
						$doc_type	= 'TA';
						$ta_acnt = 0;
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_traval_approval where emp_id = '$usrid' and status != 'Withdraw' and del !='Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
//Rejected								

								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_traval_approval where emp_id = '$usrid' and approval_status in('Rejected') and status != 'Withdraw' ";
								}
								if($department=='12' || $department=='13' || $department=='14' || $role == 'Project Manager'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_traval_approval where status != 'Withdraw' and approval_status in('Rejected') ";
									
								}

								if ($user=='Admin' || $role=='CXO' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_traval_approval where status != 'Withdraw' and approval_status in ('Rejected') ";
									//echo $sql;	
								}

								$sql .= " and del !='Y' ";
								
							//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$ta_acnt = $r1['cnt'];
								}

// Travel Expense								
						$doc_type	= 'TE';
						$te_acnt = 0;
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and status != 'Withdraw' and del !='Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
										
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and approval_status in('Rejected') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'T' and approval_status in('Rejected') ";
									
								}

								if ($user=='Admin' || $role=='CXO'){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'T' and approval_status in ('Rejected') ";
									
								}
								
								$sql .= " and del !='Y' ";
								
								//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$te_acnt = $r1['cnt'];
								}

// Regular Expense								
						$doc_type	= 'TE';
						$re_acnt = 0;
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and status != 'Withdraw' and del !='Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
										
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and approval_status in('Rejected') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'R' and approval_status in('Rejected') ";
									
								}

								if ($user=='Admin' || $role=='CXO'){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'R' and approval_status in ('Rejected') ";
									
								}
								
								$sql .= " and del !='Y' ";
								
								//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$re_acnt = $r1['cnt'];
								}
								
								
//Operating Expense								
						$doc_type	= 'CE';
						$ce_acnt = 0;
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and status != 'Withdraw' and del !='Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
										
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'C' and approval_status in('Rejected') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'C' and approval_status in('Rejected') ";
									
								}

								if ($user=='Admin' || $role=='CXO'){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'C' and approval_status in ('Rejected') ";
									
								}
								
								$sql .= " and del !='Y' ";
								
								//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$ce_acnt = $r1['cnt'];
								}
								
								
//Petty Cash								
//Rejected
					$doc_type ='PC';
					$pc_acnt = 0;
					$id_var1 = '0';
					$id_var2 = '0';
					$var_null ='';
					//echo $role. "<BR>";
								if ( $role == 'Checker - Account' || $role =='Accountant' || $role == 'Checker' || $role == 'Project Manager' || $role == 'HOD - Account' ){
									$id_var1 = '';
									$sql = " SELECT max(doc_id) as doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' ";
					//echo $sql. "<BR>";
									$qry = mysqli_query($con,$sql);
									//echo $rowaffected = mysqli_affected_rows($con);
									while($rs = mysqli_fetch_array($qry)){
										$id_var1 .= $rs['doc_id'].',';
										$var_null .= $rs['doc_id'];
									}
									if(empty($var_null)){
										$id_var1 = '0';
									}
									else {
										$id_var1 .= '0';
									}
									
									 $id_var2 = '';
									 $var_null ='';
									$sql = " SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' ";
									//echo $sql. "<BR>";
									$qry = mysqli_query($con,$sql);
									while ( $rs = mysqli_fetch_array($qry)){
										$id_var2 .= $rs['doc_id'].',';
										$var_null .= $rs['doc_id'];
									}
									if(empty($var_null)){
										$id_var2 = '0';
									}
									else {
										$id_var2 .= '0';
									}
									$sql = " SELECT approval_status, count(*) as cnt from sma_pettycash where approval_status in ( 'Rejected' ) and ( id in ( $id_var1 ) or id in ( $id_var2 ) || (draft_by = '$user') ) and del!='Y' ";
									
								}
								else {
									$sql = " SELECT approval_status, count(*) as cnt from sma_pettycash where draft_by = '$user' and approval_status in ( 'Rejected' )  and del!='Y' ";
								}	
								
								if($user=='Admin'){
									$sql = " SELECT approval_status, count(*) as cnt from sma_pettycash where approval_status in ( 'Rejected' )  and del!='Y' ";
								}
								
						//echo $sql."<BR>";		
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$rcnt_pc = $r1['cnt'];
								}
								
//Rejected End
//Petty Cash
			
?>		


	<div class="box-body">
		<form class="form-horizontal" >	
		<div class="form-group">	
                     <!--class="btn btn-lg btn-warning" -->
			<?php $i = $menu_id[2]; if ( $dashboard[$i] !='Y' ){ ?>
			<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary" data-toggle="tab" style="color:white;" onclick="getrejectAP()" >Approval Memo<br><?php echo $ap_acnt; ?></a>
			</div>
			<?php } ?>	
			<?php $i = $menu_id[3]; if ( $dashboard[$i] !='Y' ){ ?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#FF3383;color:white;" data-toggle="tab" onclick="getrejectPO()" >Purchase Order<br><?php echo $po_acnt; ?></a>
				</div>
			<?php } ?>	
			<?php 
			/*$i = $menu_id[4]; if ( $dashboard[$i] !='Y' ){ 
				$spac = '';
				for($i=0;$i<5;$i++){$spac.='&nbsp;';}
			?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#A1D521;color:white;" data-toggle="tab" onclick="getrejectGS()" ><?php echo $spac ?> GRN /SRN <?php echo $spac ?><br><?php echo $gs_acnt; ?></a>
				</div>
			<?php } */
			?>	
			<?php $i = $menu_id[5]; if ( $dashboard[$i] !='Y' ){ ?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#FC073F;color:white;" data-toggle="tab" onclick="getrejectSI()" >Supplier Invoice<br><?php echo $si_acnt; ?></a>
				</div>
			<?php } ?>	
			
			<?php $i = $menu_id[30]; if ( $dashboard[$i] !='Y' ){ ?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#F9A406;color:white;" data-toggle="tab" onclick="getrejectCE()" >Operating Expense<br><?php echo $ce_acnt; ?></a>
				</div>
			<?php } ?>	
			
			<?php $i = $menu_id[7]; if ( $dashboard[$i] !='Y' ){ 
				$spac = '';
				for($i=0;$i<11;$i++){$spac.='&nbsp;';}
			?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#3FA0A2;color:white;" data-toggle="tab" onclick="getrejectIP()" ><?php echo $spac ?> I P C <?php echo $spac ?><br><?php echo $ip_acnt; ?></a>
				</div>
			<?php } ?>	
			
			<?php $i = $menu_id[6]; if ( $dashboard[$i] !='Y' ){ 
				$spac = '';
				for($i=0;$i<6;$i++){$spac.='&nbsp;';}
			?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#D8C80D;color:white;" data-toggle="tab" onclick="getrejectPY()" ><?php echo $spac ?> Payment <?php echo $spac ?><br><?php echo $py_acnt; ?></a>
				</div>
			<?php } ?>	
		</div>
		
		<div class="form-group">
			
			<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				    <a href="#" class="btn btn-lg btn-primary123" style="background-color:orange;color:white;" data-toggle="tab" onclick="getrejectPC()" >&nbsp;&nbsp;&nbsp;&nbsp; Petty Cash &nbsp;&nbsp;&nbsp;&nbsp;<br><?php echo '&nbsp;&nbsp;'.$rcnt_pc. '&nbsp;&nbsp;' ?></a>
			</div>
			
			<?php $i = $menu_id[8]; if ( $dashboard[$i] !='Y' ){ 
				$spac = '';
				for($i=0;$i<1;$i++){$spac.='&nbsp;';}
			?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#A662EE;color:white;" data-toggle="tab" onclick="getrejectTR()" ><?php echo $spac ?>Travel Request <?php echo $spac ?><br><?php echo $ta_acnt; ?></a>
				</div>
			<?php } ?>	
			<?php $i = $menu_id[9]; if ( $dashboard[$i] !='Y' ){ ?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:#54BE41;color:white;" data-toggle="tab" onclick="getrejectTE()" >Travel Expenses<br><?php echo $te_acnt; ?></a>
				</div>
			<?php } ?>	
			<?php $i = $menu_id[30]; if ( $dashboard[$i] !='Y' ){ ?>
				<div class="col-sm-2" style="float:left; margin-top: 10px; ">
				 <a href="#" class="btn btn-lg btn-primary123" style="background-color:blue;color:white;" data-toggle="tab" onclick="getrejectRE()" >Regular Expense<br><?php echo $re_acnt; ?></a>
				</div>
			<?php } ?>	
			
			
			<div class="col-sm-4" style="float:left; margin-top: 10px; ">
				<a href="#" class="btn btn-lg btn-primary123" data-toggle="tab" >&nbsp;<br><?php echo '&nbsp;' ?></a>
			</div>
			
		</div>
		</form>
			<div class="col-sm-5" style="float:left; margin-top: 10px; ">
				<span class='mypendg'  style="font-size:26px;text-align:left;background-color:grey;color:white;">Rejected</span>
			</div>
		
		</div>
			
		
<?php		
	
        //echo $value;
    }
	
	

//Approval Notes
if(isset($_POST['sub7'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
		
            <!-- /.box-header -->
	<?php
			if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account' || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
				//$sql="SELECT DS.* from sma_approval_memo DS
				//	INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'AP' and status in('Pending', //'Verified','Prepared') 
				//	 and id in (SELECT max(id) FROM `workflow_history` where doc_Type = 'AP'  group by doc_id) ) DS1 
				//	 ON DS1.doc_id = DS.id where DS.project in (  $comid  ) and DS.approval_status in('Pending', 'Verified','Prepared') order by id desc ";
				$id_var = '';
				$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
				$qry = mysqli_query($con,$sql);
				while($rs = mysqli_fetch_array($qry)){
					$id_var .= $rs['idd'].',';
				}
				$id_var .= '0';
				$sql ="SELECT * from sma_approval_memo DS
						INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'AP' and status in('Pending', 'Verified','Prepared') 
					and id in ($id_var) ) DS1 
					ON DS1.doc_id = DS.id where DS.project in (  $comid  ) and DS.approval_status in('Pending', 'Verified','Prepared') and DS.del !='Y' ";

			}
			else {
				//$sql = "SELECT * FROM `sma_approval_memo` where project in ($comid) and approval_status in('Pending', 'Verified','Prepared')  and draft_by = '$user' order by id desc ";
				$sql = "SELECT * FROM `sma_approval_memo` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
						select max(id) as id from `workflow_history` where doc_Type = '$doc_type' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = '$doc_type' and status in('Pending', 'Verified','Prepared') ) DS1 ON sma_approval_memo.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared') and status = 'Draft' and project in ( $comid ) and draft_by = '$user' and del !='Y' ";
			}
			
			if($user == 'Admin'){
				$sql = "SELECT * FROM `sma_approval_memo` where approval_status in('Pending', 'Verified','Prepared') and del !='Y'  order by id desc ";
			}

//echo $sql;
			
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
			
			$modulePath1 = 'approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
			
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>Sr.No. </th>
                    <th>Dated</th>
					<th>Company</th>
					<th>Supplier Name</th>
					<th style="text-align:right;">Amount</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
					
				</tr>
                </thead>
                <tbody>
			<?php
					while($row = mysqli_fetch_array($result)){
						
						$approval_hdr_id = $row['id'];
						$sql 	= "SELECT `values` as total_amount FROM `sma_approval_details` where vendor_selected = 'Y' and approval_hdr_id = '$approval_hdr_id' ";
						$r4 	= mysqli_query($con, $sql);
						$r3 		= mysqli_fetch_array($r4);
						$tot_amount	= $r3['total_amount'];
						
						$company = $row['company'];
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company  = $r2['comp_name'];

						$ap_id = $row['id'];
						$sql = "SELECT b.party_name, a.values FROM `sma_approval_details` a , `sma_party_mst` b where vendor_selected = 'Y' and approval_hdr_id = '$ap_id' and b.id = a.supplier_name";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$party_name  = $r2['party_name'];
						$amount		 = $r2['values'];
						
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $j;?>" > </td>
					<td width="5%"><?php echo $row['id'];?></td>
					<td width="10%"><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
					<td width="25%"><?php echo $company;?></td>
					<td width="20%"><?php echo $party_name;?></td>
					<td width="10%" style="text-align:right;"><?php echo $amount;?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $row['approval_status'];?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
							
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		</div>	
       </div>
     </div>
	
<?php		
}	
?>

<?php
//Approval Notes - All Pending
if(isset($_POST['sub8'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];

			$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
?>

		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
			if ($role =='Maker'){
				$sql = "SELECT * FROM `sma_approval_memo` where project in ($comid) and status not in('Completed', 'Draft') and draft_by = '$user' and del !='Y' order by id desc ";
			}
			else {
				
				$sql = "SELECT * from sma_approval_memo DS
							INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') and DS.status != 'Draft' and DS.del !='Y'  order by DS.id desc ";							
			}	
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_approval_memo` where status not in('Completed', 'Draft') and del !='Y'  order by id desc ";
			}

			
			$modulePath1 = 'approval/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
			
		
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>Sr.No.</th>
                    <th>Dated</th>
					<th>Company</th>
					<th>Supplier Name</th>
					<th style="text-align:right;">Amount</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>To</th>
					
				</tr>
                </thead>
                <tbody>
			<?php
					while($row = mysqli_fetch_array($result)){
						
						$approval_hdr_id = $row['id'];
						$sql 	= "SELECT `values` as total_amount FROM `sma_approval_details` where vendor_selected = 'Y' and approval_hdr_id = '$approval_hdr_id' ";
						$r4 	= mysqli_query($con, $sql);
						$r3 		= mysqli_fetch_array($r4);
						$tot_amount	= $r3['total_amount'];
						
						$company = $row['company'];
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company  = $r2['comp_name'];

						$ap_id = $row['id'];
						$sql = "SELECT b.party_name, a.values FROM `sma_approval_details` a , `sma_party_mst` b where vendor_selected = 'Y' and approval_hdr_id = '$ap_id' and b.id = a.supplier_name";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$party_name  = $r2['party_name'];
						$amount		 = $r2['values'];
						
						$status = $row['status'];
						if($status!='Completed'){
							$status = 'Pending';
						}
						
						$srno = $row['id'];
						$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'AP' order by id desc limit 0,1 ";
					//echo $s1;	
						$res  = mysqli_query($con, $s1);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res);
						$reviewed_by		= $r1['reviewed_by'];
						$sl="SELECT * FROM sma_user where id = '$reviewed_by' ";
					
						$r3 = mysqli_query($con, $sl);
						$rw = mysqli_fetch_array($r3);
						$send_to = $rw['username'];
									
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $j;?>" > </td>
					<td width="5%"><?php echo $row['id'];?></td>
					<td width="10%"><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
					<td width="25%"><?php echo $company;?></td>
					<td width="20%"><?php echo $party_name;?></td>
					<td width="10%" style="text-align:right;"><?php echo $amount;?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $status;?></td>
					<td width="10%"><?php echo $send_to;?></td>
					
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
//Approval Notes - Approved
if(isset($_POST['sub9'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];

		$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
									
?>

		<div class="tab-content">
			<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
	
								
			if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account' || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
				//$sql="SELECT * from sma_approval_memo where project in ( $comid ) and  approval_status in('Approved') 
				//and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'AP' and status in('Approved')) 
				//or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'AP' and status in('Approved')) or draft_by = '$user' )  order by id desc ";
				$sql = "SELECT * from sma_approval_memo DS
					INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Approved') and DS.del !='Y'  order by DS.id desc";
					
			}
			
			else {
				$sql = "SELECT * FROM `sma_approval_memo` where project in ($comid) and approval_status = 'Approved' and draft_by = '$user' and del !='Y' order by id desc";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_approval_memo` where approval_status in('Approved') and del !='Y' order by id desc ";
			}
			
			$modulePath1 = 'approval/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
			
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>Sr.No.</th>
                    <th>Dated</th>
					<th>Company</th>
					<th>Supplier Name</th>
					<th style="text-align:right;">Amount</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
					
				</tr>
                </thead>
                <tbody>
			<?php
					while($row = mysqli_fetch_array($result)){
						
						$approval_hdr_id = $row['id'];
						$sql 	= "SELECT `values` as total_amount FROM `sma_approval_details` where vendor_selected = 'Y' and approval_hdr_id = '$approval_hdr_id' ";
						$r4 	= mysqli_query($con, $sql);
						$r3 		= mysqli_fetch_array($r4);
						$tot_amount	= $r3['total_amount'];
						
						$company = $row['company'];
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company  = $r2['comp_name'];

						$ap_id = $row['id'];
						$sql = "SELECT b.party_name, a.values FROM `sma_approval_details` a , `sma_party_mst` b where vendor_selected = 'Y' and approval_hdr_id = '$ap_id' and b.id = a.supplier_name";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$party_name  = $r2['party_name'];
						$amount		 = $r2['values'];
						
						$approval_status = $row['approval_status'];
						if($approval_status=='Approved'){
							$approval_status = 'Pending';
						}
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $j;?>" > </td>
					<td width="10%"><?php echo $row['id'];?></td>
					<td width="10%"><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
					<td width="20%"><?php echo $company;?></td>
					<td width="0%"><?php echo $party_name;?></td>
					<td width="10%" style="text-align:right;"><?php echo $amount;?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $row['approval_status'];?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
					
			</tr>
		</a>
				<?php } ?>
				
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
//Approval Notes - Approved
if(isset($_POST['sub10'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
				
            <!-- /.box-header -->
	<?php
	
			if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account' || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
				$sql="SELECT * from sma_approval_memo where project in ( $comid ) and  approval_status in('Rejected') 
				and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'AP' and status in('Rejected')) 
				or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'AP' and status in('Rejected')) and del !='Y'  or draft_by = '$user' )   ";
			}
			else {
				$sql = "SELECT * FROM `sma_approval_memo` where project in ($comid) and approval_status = 'Rejected' and draft_by = '$user' and del !='Y' ";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_approval_memo` where approval_status = 'Rejected' and del !='Y' ";
			}
			
			$sql .= " order by id desc  ";
			
			$modulePath1 = 'approval/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>			

              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>Sr.No.</th>
                    <th>Dated</th>
					<th>Company</th>
					<th>Supplier Name</th>
					<th style="text-align:right;">Amount</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
				
				</tr>
                </thead>
                <tbody>
			<?php
					while($row = mysqli_fetch_array($result)){
						
						$approval_hdr_id = $row['id'];
						$sql 	= "SELECT `values` as total_amount FROM `sma_approval_details` where vendor_selected = 'Y' and approval_hdr_id = '$approval_hdr_id' ";
						$r4 	= mysqli_query($con, $sql);
						$r3 		= mysqli_fetch_array($r4);
						$tot_amount	= $r3['total_amount'];
						
						$company = $row['company'];
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company  = $r2['comp_name'];

						$ap_id = $row['id'];
						$sql = "SELECT b.party_name, a.values FROM `sma_approval_details` a , `sma_party_mst` b where vendor_selected = 'Y' and approval_hdr_id = '$ap_id' and b.id = a.supplier_name";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$party_name  = $r2['party_name'];
						$amount		 = $r2['values'];
						
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $j;?>" > </td>
					<td width="10%"><?php echo $row['id'];?></td>
					<td width="10%"><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
					<td width="20%"><?php echo $company;?></td>
					<td width="20%"><?php echo $party_name;?></td>
					<td width="10%" style="text-align:right;"><?php echo $amount;?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $row['approval_status'];?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
				
			</tr>
		</a>
				<?php } ?>
				
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<!--Purchase Order-->

<?php		
//Purchase Order -  My Pending
if(isset($_POST['sub11'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
		$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
									
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">

				<!-- /.box-header -->
	<?php
			if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account' || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
				
				$id_var = '';
					$sql = "SELECT max(id) as idd, doc_id FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									
					$qry = mysqli_query($con,$sql);
					while($rs = mysqli_fetch_array($qry)){
						$id_var .= $rs['idd'].',';
						$doc_id_var .= $rs['doc_id'].',';
					}
					$id_var .= '0';
					$doc_id_var .= '0';
				$sql = "SELECT * from sma_purchase_order DS
				INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
				and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.project in ( $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') and id in ($doc_id_var) and DS.del!='Y' ";		
										
			//and reviewed_by = '$usrid'
			
			}
			else {
				//$sql = "SELECT * FROM `sma_purchase_order` where project in ($comid) and approval_status in('Pending', 'Verified','Prepared')  and draft_by = '$user' order by id desc ";
				$sql = "SELECT * FROM `sma_purchase_order` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
											select max(id) as id from `workflow_history` where doc_Type = '$doc_type' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = '$doc_type' and status in('Pending', 'Verified','Prepared') ) DS1 ON sma_purchase_order.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared') and status = 'Draft' and project in ( $comid ) and draft_by = '$user'  and del!='Y'  ";
			}
			
			if($user == 'Admin'){
				$sql = "SELECT * FROM `sma_purchase_order` where approval_status in('Pending', 'Verified','Prepared') and del !='Y' order by id desc ";
			}
			
			$modulePath1 = 'purchase_order/';

//echo $sql;
//echo $sql;
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>				

              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>PO.No.</th>
					<th>Dated</th>
					<th>Supplier</th>
					<th>Approval Notes</th>
					<th style="text-align:right;">Total</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
				
				</tr>
                </thead>
                <tbody>
			<?php
					while($row = mysqli_fetch_array($result)){
										
						
						$approval_memo_ref = $row['approval_memo_ref'];
						$sql 	= "select * from sma_approval_memo where id = '$approval_memo_ref' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$app_no_date = $approval_memo_ref. '/'.date('d-m-Y', strtotime($r2['dated']));
						
						$project = $row['project'];
						$sql 	= "select * from sma_project where id = '$project' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$project = $r2['name'];		
												
						$budget_name = $row['budget_name'];
						$sql 	= "select * from sma_budget_name where id = '$budget_name' ";
						$sql = " SELECT * FROM sma_budget_name where id in ( select budget_name from `sma_budget` where id = '$budget_name') ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$budget_name = $r2['name'];
								
						$budget_head = $row['budget_head'];
						$sql 	= "select * from sma_budget_category where id = '$budget_head' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$budget_head = $r2['category'];
						
						$to_supplier = $row['to_supplier'];
						$sql 	= "select * from sma_party_mst where id = '$to_supplier' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$to_supplier = $r2['party_name'];
					
						$purchase_id = $row['id'];
						$tot_amount = '';
						$sql="SELECT * from sma_po_items where purchase_id = '$purchase_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						while($r1 = mysqli_fetch_array($res1)){
							$qty 	= $r1['quantity'];
							$rate 	= $r1['unit_rate'];
							$gst	= $r1['gst'];
							$amount = $qty * $rate + (($qty * $rate) * $gst / 100);
							$tot_amount = $tot_amount + $amount;
						}										
						
						$rid = $row['id'];
							
						$approval_status = $row['approval_status'];
								
						$j=$j+1;						
						
						$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
					<td width="18%"><?php echo $row['po_number'];?></td>
					<td width="08%"><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
					<td width="19%"><?php echo $to_supplier;?></td>
					<td width="10%"><?php echo $app_no_date;?></td>
					<td width="10%" style="text-align:right;"><?php echo $tot_amount;?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $row['approval_status'];?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
				
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		</div>
		</div>
	</div>	
		
<?php		
}	
?>

<?php
//Purchase Order - All Pending
if(isset($_POST['sub12'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
		$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
									
?>

		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">				
						
            <!-- /.box-header -->
	<?php
	
			if ($role =='Maker'){
				$sql = "SELECT * FROM `sma_purchase_order` where project in ($comid) and status not in('Completed', 'Draft') and draft_by = '$user' and del !='Y' order by id desc ";
			}
			else {
				//$sql = "SELECT * FROM `sma_purchase_order` where project in ($comid) and status not in('Completed', 'Draft') order by id desc ";
				//$sql = "SELECT * from sma_purchase_order DS
				//						INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
				//						and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid'  group by //doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared')";
				$sql="SELECT DS.* from sma_purchase_order DS
					INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
					and id in ($id_var) ) DS1 
					ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') and DS.del !='Y'  order by id desc ";
										
			}
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_purchase_order` where status not in('Completed', 'Draft') and del !='Y' order by id desc ";
			}
			
			$modulePath1 = 'purchase_order/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
        <div class="col-md-12">
			<div class="box"> </div>	
			
			<table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>PO.No.</th>
					<th>Dated</th>
					<th>Supplier</th>
					<th>Approval Notes</th>
					<th style="text-align:right;">Total</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>To</th>
				
				</tr>
                </thead>
                <tbody>
			<?php
					while($row = mysqli_fetch_array($result)){
										
						
						$approval_memo_ref = $row['approval_memo_ref'];
						$sql 	= "select * from sma_approval_memo where id = '$approval_memo_ref' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$app_no_date = $approval_memo_ref. '/'.date('d-m-Y', strtotime($r2['dated']));
						
						
						$project = $row['project'];
						$sql 	= "select * from sma_project where id = '$project' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$project = $r2['name'];		
												
						$budget_name = $row['budget_name'];
						$sql 	= "select * from sma_budget_name where id = '$budget_name' ";
						$sql = " SELECT * FROM sma_budget_name where id in ( select budget_name from `sma_budget` where id = '$budget_name') ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$budget_name = $r2['name'];
								
						$budget_head = $row['budget_head'];
						$sql 	= "select * from sma_budget_category where id = '$budget_head' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$budget_head = $r2['category'];
						
						$to_supplier = $row['to_supplier'];
						$sql 	= "select * from sma_party_mst where id = '$to_supplier' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$to_supplier = $r2['party_name'];
					
						$purchase_id = $row['id'];
						$tot_amount = '';
						$sql="SELECT * from sma_po_items where purchase_id = '$purchase_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						while($r1 = mysqli_fetch_array($res1)){
							$qty 	= $r1['quantity'];
							$rate 	= $r1['unit_rate'];
							$gst	= $r1['gst'];
							$amount = $qty * $rate + (($qty * $rate) * $gst / 100);
							$tot_amount = $tot_amount + $amount;
						}										
						
						$rid = $row['id'];
						
						$status = $row['status'];
						if($status!='Completed'){
							$status = 'Pending';
						}
						
						$srno = $row['id'];
						$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'PO' order by id desc limit 0,1 ";
					//echo $s1;	
						$res  = mysqli_query($con, $s1);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res);
						$reviewed_by		= $r1['reviewed_by'];
						$sl="SELECT * FROM sma_user where id = '$reviewed_by' ";
					
						$r3 = mysqli_query($con, $sl);
						$rw = mysqli_fetch_array($r3);
						$send_to = $rw['username'];
						
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
					<td width="18%"><?php echo $row['po_number'];?></td>
					<td width="08%"><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
					<td width="19%"><?php echo $to_supplier;?></td>
					<td width="10%"><?php echo $app_no_date;?></td>
					<td width="10%" style="text-align:right;"><?php echo $tot_amount;?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $status;?></td>
					<td width="10%"><?php echo $send_to;?></td>
				
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
//Purchase Order - Approved
if(isset($_POST['sub13'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
		$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
									
?>

		<div class="tab-content">
			<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
	
			if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account' || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
				//$sql="SELECT * from sma_purchase_order where project in ( $comid ) and  approval_status in('Approved') 
				//and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'PO' and status in('Approved')) 
				//or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PO' and status in('Approved')) or draft_by = '$user' )  order by id desc ";
				$sql = "SELECT DS.* from sma_purchase_order DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PO' and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Approved') and del !='Y' order by DS.id desc "	;
			}
			else {
				$sql = "SELECT * FROM `sma_purchase_order` where project in ($comid) and approval_status = 'Approved' and draft_by = '$user' and del !='Y' order by id desc";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_purchase_order` where approval_status in('Approved') and del !='Y' order by id desc ";
			}
			
			
			$modulePath1 = 'purchase_order/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>			
	
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>PO.No.</th>
					<th>Dated</th>
					<th>Supplier</th>
					<th>Approval Notes</th>
					<th style="text-align:right;">Total</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
				</tr>
                </thead>
                <tbody>
			<?php
					while($row = mysqli_fetch_array($result)){
										
						
						$approval_memo_ref = $row['approval_memo_ref'];
						$sql 	= "select * from sma_approval_memo where id = '$approval_memo_ref' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$app_no_date = $approval_memo_ref. '/'.date('d-m-Y', strtotime($r2['dated']));
						
						
						$project = $row['project'];
						$sql 	= "select * from sma_project where id = '$project' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$project = $r2['name'];		
												
						$budget_name = $row['budget_name'];
						$sql 	= "select * from sma_budget_name where id = '$budget_name' ";
						$sql = " SELECT * FROM sma_budget_name where id in ( select budget_name from `sma_budget` where id = '$budget_name') ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$budget_name = $r2['name'];
								
						$budget_head = $row['budget_head'];
						$sql 	= "select * from sma_budget_category where id = '$budget_head' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$budget_head = $r2['category'];
						
						$to_supplier = $row['to_supplier'];
						$sql 	= "select * from sma_party_mst where id = '$to_supplier' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$to_supplier = $r2['party_name'];
					
						$purchase_id = $row['id'];
						$tot_amount = '';
						$sql="SELECT * from sma_po_items where purchase_id = '$purchase_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						while($r1 = mysqli_fetch_array($res1)){
							$qty 	= $r1['quantity'];
							$rate 	= $r1['unit_rate'];
							$gst	= $r1['gst'];
							$amount = $qty * $rate + (($qty * $rate) * $gst / 100);
							$tot_amount = $tot_amount + $amount;
						}										
						
						$rid = $row['id'];
							
						$approval_status = $row['approval_status'];
							
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
					<td width="18%"><?php echo $row['po_number'];?></td>
					<td width="08%"><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
					<td width="19%"><?php echo $to_supplier;?></td>
					<td width="10%"><?php echo $app_no_date;?></td>
					<td width="10%" style="text-align:right;"><?php echo $tot_amount;?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $row['approval_status'];?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
			
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
//Purchase Order - Rejected
if(isset($_POST['sub14'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>

		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
	
			if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account' || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
				$sql="SELECT * from sma_purchase_order where project in ( $comid ) and  approval_status in('Rejected') 
				and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'PO' and status in('Rejected')) 
				or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PO' and status in('Rejected')) ) ";
			}
			else {
				$sql = "SELECT * FROM `sma_purchase_order` where project in ($comid) and approval_status = 'Rejected' and draft_by = '$user' ";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_purchase_order` where approval_status = 'Rejected' ";
			}
			
			$sql .= " and del !='Y' ";
			$sql .= ' order by id desc ';
			
			$modulePath1 = 'purchase_order/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>			

              <table id="prtable" class="table table-bordered table-striped">
                <thead>
            <tr>
                    <th></th>
					<th>PO.No.</th>
					<th>Dated</th>
					<th>Supplier</th>
					<th>Approval Notes</th>
					<th style="text-align:right;">Total</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
				
				</tr>
                </thead>
                <tbody>
			<?php
					while($row = mysqli_fetch_array($result)){
										
						$approval_memo_ref = $row['approval_memo_ref'];
						$sql 	= "select * from sma_approval_memo where id = '$approval_memo_ref' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$app_no_date = $approval_memo_ref. '/'.date('d-m-Y', strtotime($r2['dated']));
						
						$project = $row['project'];
						$sql 	= "select * from sma_project where id = '$project' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$project = $r2['name'];		
												
						$budget_name = $row['budget_name'];
						$sql 	= "select * from sma_budget_name where id = '$budget_name' ";
						$sql = " SELECT * FROM sma_budget_name where id in ( select budget_name from `sma_budget` where id = '$budget_name') ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$budget_name = $r2['name'];
								
						$budget_head = $row['budget_head'];
						$sql 	= "select * from sma_budget_category where id = '$budget_head' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$budget_head = $r2['category'];
						
						$to_supplier = $row['to_supplier'];
						$sql 	= "select * from sma_party_mst where id = '$to_supplier' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$to_supplier = $r2['party_name'];
					
						$purchase_id = $row['id'];
						$tot_amount = '';
						$sql="SELECT * from sma_po_items where purchase_id = '$purchase_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						while($r1 = mysqli_fetch_array($res1)){
							$qty 	= $r1['quantity'];
							$rate 	= $r1['unit_rate'];
							$gst	= $r1['gst'];
							$amount = $qty * $rate + (($qty * $rate) * $gst / 100);
							$tot_amount = $tot_amount + $amount;
						}										
						
						$rid = $row['id'];
							
						$approval_status = $row['approval_status'];
							
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
					<td width="18%"><?php echo $row['po_number'];?></td>
					<td width="08%"><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
					<td width="19%"><?php echo $to_supplier;?></td>
					<td width="10%"><?php echo $app_no_date;?></td>
					<td width="10%" style="text-align:right;"><?php echo $tot_amount;?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $row['approval_status'];?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
			</tr>
		</a>
				<?php } ?>	
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>
<!--Purchase Order-->


<!--GRN SRN-->
<?php		
//GRN SRN -  My Pending
if(isset($_POST['sub15'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
		$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
									
?>

		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
			if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account' || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
				//$sql="SELECT * from sma_grn_srn where  approval_status in('Pending', 'Verified','Prepared')  
				//and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'GS' and status in('Pending', 'Verified','Prepared') ) )  order by id desc ";
				$sql = "SELECT DS.* from sma_grn_srn DS
						INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
						and id in ($id_var) ) DS1 
						ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Prepared') and DS.del !='Y' order by id desc";
			}
			else {
				//$sql = "SELECT * FROM `sma_grn_srn` where approval_status in('Pending', 'Verified','Prepared')  and draft_by = '$user' order by id desc ";
				$sql = "SELECT * FROM `sma_grn_srn` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
						select max(id) as id from `workflow_history` where doc_Type = '$doc_type' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = '$doc_type' and status in('Pending', 'Verified','Prepared') ) DS1 ON sma_grn_srn.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared') and status = 'Draft' and draft_by = '$user' and del !='Y' order by id desc";
			}
			
			if($user == 'Admin'){
				$sql = "SELECT * FROM `sma_grn_srn` where approval_status in('Pending', 'Verified','Prepared') and del !='Y' order by id desc ";
			}
			
			
			$modulePath1 = 'grnsrn/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>			

              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th></th>
					<th>SrNo.</th>
					<th>Dated</th>
					<th>Supplier Name</th>
					<th>Supp.Inv.No.</th>
					<th>Our PO Ref.NO.</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
				while($row = mysqli_fetch_array($result)){
					$supplier_id = $row['supplier_name'];
					$sql="SELECT * from sma_party_mst where id = '$supplier_id' ";
					$q2 = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$supplier_name = $r2['party_name'];
					
					$our_po_ref_no = $row['our_po_ref_no'];
					$sql="SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
					$q2 = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$our_po_ref_no = $r2['po_number'];
					
					$j=$j+1;
					
					$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
								
						?>
					<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
					<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
						<td width="0%"><input type="hidden" value="<?php echo $j;?>" > </td>
						<td width="6%" style="text-align:right;"><?php echo $row['id'];?></td>
						<td width="08%"><?php echo date('d-m-Y', strtotime($row['received_date']));?></td>
						<td width="23%"><?php echo $supplier_name;?></td>
						<td width="09%"><?php echo $row['supplier_invoice_no'];?></td>
						<td width="17%"><?php echo $our_po_ref_no;?></td>
						
						<td width="09%"><?php echo $row['changed_by'];?></td>
						<td width="09%"><?php echo $row['status'];?></td>
						<td width="09%"><?php echo $row['approval_status'];?></td>	
		
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
//GRN SRN - All Pending
if(isset($_POST['sub16'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>

		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
			
			if ($role =='Maker'){
				$sql = "SELECT * FROM `sma_grn_srn` where status not in('Completed', 'Draft') and draft_by = '$user' ";
			}
			else {
				$sql = "SELECT * FROM `sma_grn_srn` where status not in('Completed', 'Draft') ";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_grn_srn` where status not in('Completed', 'Draft') ";
			}
			
			$sql .= " and del !='Y' ";
			$sql .= " order by id desc  ";
			
			$modulePath1 = 'grnsrn/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
					
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th></th>
					<th>SrNo.</th>
					<th>Dated</th>
					<th>Supplier Name</th>
					<th>Supp.Inv.No.</th>
					<th>Our PO Ref.NO.</th>
					<th>By</th>
					<th>Status</th>
					<th>To</th>
				</tr>
                </thead>
                <tbody>
			<?php
				while($row = mysqli_fetch_array($result)){
					$supplier_id = $row['supplier_name'];
					$sql="SELECT * from sma_party_mst where id = '$supplier_id' ";
					$q2 = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$supplier_name = $r2['party_name'];
					
					$our_po_ref_no = $row['our_po_ref_no'];
					$sql="SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
					$q2 = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$our_po_ref_no = $r2['po_number'];

						$status = $row['status'];
						if($status!='Completed'){
							$status = 'Pending';
						}
					
					$srno = $row['id'];
						$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'GS' order by id desc limit 0,1 ";
					//echo $s1;	
						$res  = mysqli_query($con, $s1);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res);
						$reviewed_by		= $r1['reviewed_by'];
						$sl="SELECT * FROM sma_user where id = '$reviewed_by' ";
					
						$r3 = mysqli_query($con, $sl);
						$rw = mysqli_fetch_array($r3);
						$send_to = $rw['username'];
						
						
					$j=$j+1;
					
					$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
								
						?>
					<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
					<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
						<td width="0%"><input type="hidden" value="<?php echo $j;?>" > </td>
						<td width="6%" style="text-align:right;"><?php echo $row['id'];?></td>
						<td width="08%"><?php echo date('d-m-Y', strtotime($row['received_date']));?></td>
						<td width="23%"><?php echo $supplier_name;?></td>
						<td width="09%"><?php echo $row['supplier_invoice_no'];?></td>
						<td width="17%"><?php echo $our_po_ref_no;?></td>
						
						<td width="09%"><?php echo $row['changed_by'];?></td>
						<td width="09%"><?php echo $status;?></td>
						<td width="09%"><?php echo $send_to;?></td>	
		
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
//GRN SRN - Approved
if(isset($_POST['sub17'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';		
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
	
			if ($role=='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account' || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
				//$sql="SELECT * from sma_grn_srn where approval_status in('Approved') 
				//and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'GS' and status in('Approved')) 
				//or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'GS' and status in('Approved')) or draft_by = '$user' )  order by id desc ";
				$sql="SELECT *  from sma_grn_srn DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'GS' and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.approval_status in('Approved') and DS.del !='Y' order by DS.id desc";
			}
			else {
				$sql = "SELECT * FROM `sma_grn_srn` where approval_status = 'Approved' and draft_by = '$user' and del !='Y' order by id desc";
			}
			
			if($user == 'Admin'|| $role == 'CXO'){
				$sql = "SELECT * FROM `sma_grn_srn` where approval_status in('Approved') and del !='Y' order by id desc ";
			}
			
			$modulePath1 = 'grnsrn/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
					
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
            <tr>
					<th></th>
					<th>SrNo.</th>
					<th>Dated</th>
					<th>Supplier Name</th>
					<th>Supp.Inv.No.</th>
					<th>Our PO Ref.NO.</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
				while($row = mysqli_fetch_array($result)){
					$supplier_id = $row['supplier_name'];
					$sql="SELECT * from sma_party_mst where id = '$supplier_id' ";
					$q2 = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$supplier_name = $r2['party_name'];
					
					$our_po_ref_no = $row['our_po_ref_no'];
					$sql="SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
					$q2 = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$our_po_ref_no = $r2['po_number'];
					
					$j=$j+1;
					
					$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
								
						?>
					<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
					<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
						<td width="0%"><input type="hidden" value="<?php echo $j;?>" > </td>
						<td width="6%" style="text-align:right;"><?php echo $row['id'];?></td>
						<td width="08%"><?php echo date('d-m-Y', strtotime($row['received_date']));?></td>
						<td width="23%"><?php echo $supplier_name;?></td>
						<td width="09%"><?php echo $row['supplier_invoice_no'];?></td>
						<td width="17%"><?php echo $our_po_ref_no;?></td>
						
						<td width="09%"><?php echo $row['changed_by'];?></td>
						<td width="09%"><?php echo $row['status'];?></td>
						<td width="09%"><?php echo $row['approval_status'];?></td>	
		
			</tr>
					</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
//GRN SRN - Rejected
if(isset($_POST['sub18'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
	
			if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account' || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
				$sql="SELECT * from sma_grn_srn where approval_status in('Rejected') 
				and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'GS' and status in('Rejected')) 
				or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'GS' and status in('Rejected')) ) ";
			}
			else {
				$sql = "SELECT * FROM `sma_grn_srn` where approval_status = 'Rejected' and draft_by = '$user' ";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_grn_srn` where approval_status = 'Rejected'  ";
			}
			
			$sql .= " and del !='Y' ";
			$sql.=' order by id desc ';
			
			$modulePath1 = 'grnsrn/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
					
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th></th>
					<th>SrNo.</th>
					<th>Dated</th>
					<th>Supplier Name</th>
					<th>Supp.Inv.No.</th>
					<th>Our PO Ref.NO.</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
				while($row = mysqli_fetch_array($result)){
					$supplier_id = $row['supplier_name'];
					$sql="SELECT * from sma_party_mst where id = '$supplier_id' ";
					$q2 = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$supplier_name = $r2['party_name'];
					
					$our_po_ref_no = $row['our_po_ref_no'];
					$sql="SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
					$q2 = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$our_po_ref_no = $r2['po_number'];
					
					$j=$j+1;
					
					$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
								
						?>
					<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
					<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
						<td width="0%"><input type="hidden" value="<?php echo $j;?>" > </td>
						<td width="6%" style="text-align:right;"><?php echo $row['id'];?></td>
						<td width="08%"><?php echo date('d-m-Y', strtotime($row['received_date']));?></td>
						<td width="23%"><?php echo $supplier_name;?></td>
						<td width="09%"><?php echo $row['supplier_invoice_no'];?></td>
						<td width="17%"><?php echo $our_po_ref_no;?></td>
						
						<td width="09%"><?php echo $row['changed_by'];?></td>
						<td width="09%"><?php echo $row['status'];?></td>
						<td width="09%"><?php echo $row['approval_status'];?></td>	
		
			</tr>
					</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<!--GRN SRN-->

<!--Supplier Invoice-->
<?php		
//Supplier Invoice -  My Pending

//  $_POST['sub19'];

if(isset($_POST['sub19'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
									
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
			if ($role =='Checker' || $role =='Checker - Account' || $role =='HOD - Account' || $role =='Project Manager' || $role =='Project Incharge' || $role =='CXO' || $role =='HOD' ){
					
				$sql="SELECT * from sma_supplier_invoice DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared', 'Submited') 
											and id in ($id_var) ) DS1 
											ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') and DS.del !='Y' order by DS.id desc";
				$sql = "SELECT * from sma_supplier_invoice DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared', 'Submited') 
											and id in ($id_var) ) DS1 
											ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared')  and DS.del!='Y' order by DS.id desc ";	
									
			}
			else if ($role =='Accountant'  ){
				$sql = "SELECT * from sma_supplier_invoice DS
						INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared','Submited') 
					and id in ($id_var) ) DS1 
					ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') and DS.del !='Y' order by DS.id desc";	
//echo $sql;											
			}
			else if ( $role =='Maker' ){
			//	$sql="SELECT * from sma_supplier_invoice where company_id in ( $comid ) and  approval_status in('Pending', 'Verified','Prepared') 
			//			and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'SI') 
			//			or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'SI') or draft_by = '$user' ) order by id desc ";
				$sql = "SELECT * FROM `sma_supplier_invoice` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
						select max(id) as id from `workflow_history` where doc_Type = '$doc_type' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = '$doc_type' and status in('Pending', 'Verified','Prepared', 'Submited') ) DS1 ON sma_supplier_invoice.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared') and status = 'Draft' and company_id in ( $comid ) and draft_by = '$user' and del !='Y' order by id desc";		
			}
			else {
				$sql = "SELECT * FROM `sma_supplier_invoice` where company_id in ($comid) and approval_status in('Pending', 'Verified','Prepared')  and draft_by = '$user' and del !='Y' order by id desc";
			}
								
	//		if($user == 'Admin'){
	//			$sql = "SELECT * FROM `sma_supplier_invoice` where approval_status in('Pending', 'Verified','Prepared') order by id desc ";
	//		}
			
//echo $sql;
			
			//$sql .= " and del !='Y' ";
			
			$modulePath1 = 'supp_invoice/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
					
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
        			<th>#</th>
					<th>SrNo</th>
					<th>Dated</th>
					<th>Supp.Inv.No.</th>
					<th style="text-align:right;">Amount</th>
					<th>Our PO Ref.NO.</th>
					<th>Due Date</th>
					<th>Supplier Name</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
		
				</tr>
                </thead>
                <tbody>
			<?php
				$j  =0;
			while($row = mysqli_fetch_array($result)){
						
				$supplier = $row['suplier_name'];
				$sql 	= "select * from sma_party_mst where id = '$supplier' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$supplier_name = $r2['party_name'];

				$our_po_ref_no = $row['our_po_ref_no'];
				
				$rid = $row['id'];
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				$j = $j + 1;		
				?>
			<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
				<td width="0%" > <input type="hidden" style="text-align:right;"  value="<?php echo $j?>" ></td>	
				<td width="6%" style="text-align:right;"><?php echo $row['id']?></td>
				<td width="10%"><?php echo date('d-m-Y', strtotime($row['invoice_date']));?></td>
				<td width="10%"><?php echo $row['supplier_invoice_no'];?></td>
				<td width="10%" style="text-align:right;"><?php echo $row['total_amount']?></td>
				<td width="15%"><?php echo $our_po_ref_no;?></td>
				<td width="10%"><?php echo date('d-m-Y', strtotime($row['due_date']));?></td>
				<td width="20%"><?php echo $supplier_name;?></td>
				<td width="10%"><?php echo $row['changed_by'];?></td>
				<td width="09%"><?php echo $row['status'];?></td>
				<td width="10%"><?php echo $row['approval_status'];?></td>
			
			</tr>
			</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
//Supplier Invoice - All Pending
if(isset($_POST['sub20'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
?>
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
			if ($role =='Maker'){
				$sql = "SELECT * FROM `sma_supplier_invoice` where company_id in ( $comid ) and  status not in('Completed', 'Draft') and draft_by = '$user' ";
			}
			else {
				$sql = "SELECT * FROM `sma_supplier_invoice` where company_id in ( $comid ) and  status not in('Completed', 'Draft')  ";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_supplier_invoice` where status not in('Completed', 'Draft')  ";
			}
			
			$sql .= " and del !='Y' ";
			$sql .= " order by id desc ";
			
			$modulePath1 = 'supp_invoice/';

			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
					
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
        			<th>#</th>
					<th>SrNo</th>
					<th>Dated</th>
					<th>Supp.Inv.No.</th>
					<th style="text-align:right;">Amount</th>
					<th>Our PO Ref.NO.</th>
					<th>Due Date</th>
					<th>Supplier Name</th>
					<th>By</th>
					<th>Status</th>
					<th>TO</th>
		
				</tr>
                </thead>
                <tbody>
			<?php
			$j = 0;
			while($row = mysqli_fetch_array($result)){
						
				$supplier = $row['suplier_name'];
				$sql 	= "select * from sma_party_mst where id = '$supplier' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$supplier_name = $r2['party_name'];

				$our_po_ref_no = $row['our_po_ref_no'];
						
						$status = $row['status'];
						if($status!='Completed'){
							$status = 'Pending';
						}
				$srno = $row['id'];
						$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'SI' order by id desc limit 0,1 ";
					//echo $s1;	
						$res  = mysqli_query($con, $s1);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res);
						$reviewed_by		= $r1['reviewed_by'];
						$sl="SELECT * FROM sma_user where id = '$reviewed_by' ";
					
						$r3 = mysqli_query($con, $sl);
						$rw = mysqli_fetch_array($r3);
						$send_to = $rw['username'];
								
				$rid = $row['id'];
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				$j = $j + 1;		
						
				?>
			<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
				<td width="0%" > <input type="hidden" style="text-align:right;"  value="<?php echo $j?>" ></td>	
				<td width="6%" style="text-align:right;"><?php echo $row['id']?></td>
				<td width="10%"><?php echo date('d-m-Y', strtotime($row['invoice_date']));?></td>
				<td width="10%"><?php echo $row['supplier_invoice_no'];?></td>
				<td width="10%" style="text-align:right;"><?php echo $row['total_amount']?></td>
				<td width="15%"><?php echo $our_po_ref_no;?></td>
				<td width="10%"><?php echo date('d-m-Y', strtotime($row['due_date']));?></td>
				<td width="20%"><?php echo $supplier_name;?></td>
				<td width="10%"><?php echo $row['changed_by'];?></td>
				<td width="09%"><?php echo $status;?></td>
				<td width="10%"><?php echo $send_to;?></td>
			
			</tr>
			</a>

				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
//Supplier Invoice - Approved
if(isset($_POST['sub21'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';		
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
	
			if ($role =='Checker' || $role =='Accountant'){
				//$sql="SELECT * from sma_supplier_invoice where id in (SELECT distinct(si_hdr_id) FROM `sma_supplier_invoice_details` where company_id in ( $comid ) ) and  approval_status in('Approved') and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'SI') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'SI')) or draft_by = '$user'";	
				$sql = "SELECT * from sma_supplier_invoice DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Approved') and DS.del !='Y' order by DS.id desc";
			}
			else if (  $role =='Checker - Account' || $role =='HOD - Account' || $role =='Maker' ){
				$sql="SELECT * from sma_supplier_invoice where company_id in ( $comid ) and  approval_status in('Approved') 
						and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'SI') 
						or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'SI') or draft_by = '$user' ) and del !='Y' order by id desc ";
			}
			else {
				$sql = "SELECT * FROM `sma_supplier_invoice` where company_id in ( $comid ) and approval_status = 'Approved' and draft_by = '$user' and del !='Y' order by id desc";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_supplier_invoice` where approval_status in('Approved') and del !='Y' order by id desc ";
			}
			
			$modulePath1 = 'supp_invoice/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
					
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                <tr>
        			<th>#</th>
					<th>SrNo</th>
					<th>Dated</th>
					<th>Supp.Inv.No.</th>
					<th style="text-align:right;">Amount</th>
					<th>Our PO Ref.NO.</th>
					<th>Due Date</th>
					<th>Supplier Name</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
		
				</tr>
                </thead>
                <tbody>
			<?php
			$j = 0;
			while($row = mysqli_fetch_array($result)){
						
				$supplier = $row['suplier_name'];
				$sql 	= "select * from sma_party_mst where id = '$supplier' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$supplier_name = $r2['party_name'];

				$our_po_ref_no = $row['our_po_ref_no'];
				
				$rid = $row['id'];
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				$j = $j + 1;		
						
				?>
			<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
				<td width="0%" > <input type="hidden" style="text-align:right;"  value="<?php echo $j?>" ></td>	
				<td width="6%" style="text-align:right;"><?php echo $row['id']?></td>
				<td width="10%"><?php echo date('d-m-Y', strtotime($row['invoice_date']));?></td>
				<td width="10%"><?php echo $row['supplier_invoice_no'];?></td>
				<td width="10%" style="text-align:right;"><?php echo $row['total_amount']?></td>
				<td width="15%"><?php echo $our_po_ref_no;?></td>
				<td width="10%"><?php echo date('d-m-Y', strtotime($row['due_date']));?></td>
				<td width="20%"><?php echo $supplier_name;?></td>
				<td width="10%"><?php echo $row['changed_by'];?></td>
				<td width="09%"><?php echo $row['status'];?></td>
				<td width="10%"><?php echo $row['approval_status'];?></td>
			
			</tr>
			</a>

				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
//Supplier Invoice - Rejected
if(isset($_POST['sub22'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
	
			if ($role =='Checker' || $role =='Accountant'){
				$sql="SELECT * from sma_supplier_invoice where id in (SELECT distinct(si_hdr_id) FROM `sma_supplier_invoice_details` where company_id in ( $comid ) ) and  approval_status in('Rejected') and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'SI') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'SI')) and del !='Y' or draft_by = '$user' order by id desc";	
			}
			else if (  $role =='Checker - Account' || $role =='HOD - Account' || $role =='Maker' ){
				$sql = "SELECT * from sma_supplier_invoice where company_id in ( $comid ) and  approval_status in('Rejected') 
						and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'SI') 
						or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'SI') and del !='Y' or draft_by = '$user' )  order by id desc ";
			}
			else {
				$sql = "SELECT * FROM `sma_supplier_invoice` where company_id in ( $comid ) and approval_status = 'Rejected' and draft_by = '$user' and del !='Y' order by id desc ";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_supplier_invoice` where approval_status = 'Rejected' and del !='Y' order by id desc ";
			}
			
			$modulePath1 = 'supp_invoice/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
					
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
        			<th>#</th>
					<th>SrNo</th>
					<th>Dated</th>
					<th>Supp.Inv.No.</th>
					<th style="text-align:right;">Amount</th>
					<th>Our PO Ref.NO.</th>
					<th>Due Date</th>
					<th>Supplier Name</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
		
				</tr>
                </thead>
                <tbody>
			<?php
			$j = 0;
			while($row = mysqli_fetch_array($result)){
						
				$supplier = $row['suplier_name'];
				$sql 	= "select * from sma_party_mst where id = '$supplier' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$supplier_name = $r2['party_name'];

				$our_po_ref_no = $row['our_po_ref_no'];
				
				$rid = $row['id'];
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				$j = $j + 1;		
						
			?>
			<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
				<td width="0%" > <input type="hidden" style="text-align:right;"  value="<?php echo $j?>" ></td>	
				<td width="6%" style="text-align:right;"><?php echo $row['id']?></td>
				<td width="10%"><?php echo date('d-m-Y', strtotime($row['invoice_date']));?></td>
				<td width="10%"><?php echo $row['supplier_invoice_no'];?></td>
				<td width="10%" style="text-align:right;"><?php echo $row['total_amount']?></td>
				<td width="15%"><?php echo $our_po_ref_no;?></td>
				<td width="10%"><?php echo date('d-m-Y', strtotime($row['due_date']));?></td>
				<td width="20%"><?php echo $supplier_name;?></td>
				<td width="10%"><?php echo $row['changed_by'];?></td>
				<td width="09%"><?php echo $row['status'];?></td>
				<td width="10%"><?php echo $row['approval_status'];?></td>
			
			</tr>
			</a>

				<?php } ?>
				                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<!--Supplier Invoice-->


<!--IPC-->

<?php		
//IPC -  My Pending
if(isset($_POST['sub23'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
			if ($role =='HOD - Account' || $role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge'  || $role == 'HOD' || $role == 'CXO' ){
				$sql="SELECT * from sma_ipc where sma_comp_id in ( $comid ) and  approval_status in('Pending', 'Verified','Prepared')  
				and (id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type'  ) and del !='Y' or draft_by = '$user' )  order by id desc ";
			//echo $sql;	
			}
			else {
				//$sql = "SELECT * FROM `sma_ipc` where sma_comp_id in ( $comid ) and approval_status in('Pending', 'Verified','Prepared')  and draft_by = '$user' order by id desc ";
				$sql = "SELECT * FROM `sma_ipc` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
						select max(id) as id from `workflow_history` where doc_Type = '$doc_type' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = '$doc_type' and status in('Pending', 'Verified','Prepared') ) DS1 ON sma_ipc.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared') and status = 'Draft' and sma_comp_id in ( $comid ) and draft_by = '$user' and del !='Y' order by id desc ";	
			}
			
//			if($user == 'Admin'){
//				$sql = "SELECT * FROM `sma_ipc` where approval_status in('Pending', 'Verified','Prepared') order by id desc ";
//			}
								
	//$user   = $_SESSION['user'];
								
						if($user == 'Admin' ){
							$sql = "SELECT * FROM `sma_ipc` where approval_status in('Pending', 'Verified','Prepared') and del !='Y'  order by id desc ";
						}
			
			$modulePath1 = 'ipc/';
//echo $sql;
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
		
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
        			<th>Sr.No.</th>
					<th>Dated</th>
					<th>Company</th>
					<th>Party</th>
					<th>PO.Number</th>
					<th>Invoice No.</th>
					<th>PO Amount</th>
					<th>Invoice Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
	
				</tr>
                </thead>
                <tbody>
			<?php
			$j = 0;
			while($row = mysqli_fetch_array($result)){
						
				$sma_vendor_id = $row['sma_vendor_id'];
				$sql = "select * from sma_party_mst where id = '$sma_vendor_id' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$sma_vendor_name = $r2['party_name'];
				
				$sma_comp_id = $row['sma_comp_id'];
				$sql = "select * from company where comp_id = '$sma_comp_id' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$comp_name = $r2['comp_name'];
				
				$sma_po_no = $row['sma_po_no'];
				$sql = "select * from sma_purchase_order where id = '$sma_po_no' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$po_number = $r2['po_number'];
				
				$sma_invoice_no = $row['sma_invoice_no'];
				$sql = "select * from sma_supplier_invoice where id = '$sma_invoice_no' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$sma_invoice_no = $r2['supplier_invoice_no'];
				
				$ipc_date	= date('d-m-Y', strtotime($row['ipc_date']));
				if($ipc_date == '01-01-1970'){
					$ipc_date	='';
				}
				$baseurl1 = $baseurl.$modulePath1.'ipc.php?sub=edit&id='.$row["id"];
				$j = $j +1;
		?>

				<a href="<?php echo $baseurl . $modulePath1 . "ipc.php?sub=edit&id=". $row['id']?>" title="Edit">
				<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="0%" > <input type="hidden" style="text-align:right;"  value="<?php echo $j?>" ></td>	
					<td width="5%"><?php echo $row['id'];?></td>
					<td width="8%"><?php echo $ipc_date;?></td>
					<td width="20%"><?php echo $comp_name;?></td>
					<td width="15%"><?php echo $sma_vendor_name;?></td>
					<td width="9%"><?php echo $po_number;?></td>
					<td width="8%"><?php echo $sma_invoice_no;?></td>
					<td width="9%"><?php echo $row['sma_po_amount'];?></td>
					<td width="9%"><?php echo $row['sma_invoice_amount'];?></td>
					<td width="8%"><?php echo $row['changed_by'];?></td>
					<td width="8%"><?php echo $row['status'];?></td>
					<td width="8%"><?php echo $row['approval_status'];?></td>

				</tr>
				</a>

			<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
//IPC - All Pending
if(isset($_POST['sub24'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
		$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';		
?>

		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
	
			if ($role =='Maker'){
				$sql = "SELECT * FROM `sma_ipc` where sma_comp_id in ( $comid ) and status not in('Completed', 'Draft') and draft_by = '$user' and del !='Y' order by id desc ";
			}
			else {
				//$sql = "SELECT * FROM `sma_ipc` where sma_comp_id in ( $comid ) and status not in('Completed', 'Draft') order by id desc ";	
				$sql ="SELECT * from sma_ipc DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
										and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.sma_comp_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') and DS.del !='Y' order by DS.id desc";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_ipc` where status not in('Completed', 'Draft') and del !='Y' order by id desc ";
			}
			$modulePath1 = 'ipc/';
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
		
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
        			<th>Sr.No.</th>
					<th>Dated</th>
					<th>Company</th>
					<th>Party</th>
					<th>PO.Number</th>
					<th>Invoice No.</th>
					<th>PO Amount</th>
					<th>Invoice Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>To</th>
	
				</tr>
                </thead>
                <tbody>
			<?php
			$j = 0;
			while($row = mysqli_fetch_array($result)){
						
				$sma_vendor_id = $row['sma_vendor_id'];
				$sql = "select * from sma_party_mst where id = '$sma_vendor_id' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$sma_vendor_name = $r2['party_name'];
				
				$sma_comp_id = $row['sma_comp_id'];
				$sql = "select * from company where comp_id = '$sma_comp_id' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$comp_name = $r2['comp_name'];
				
				$sma_po_no = $row['sma_po_no'];
				$sql = "select * from sma_purchase_order where id = '$sma_po_no' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$po_number = $r2['po_number'];
				
				$sma_invoice_no = $row['sma_invoice_no'];
				$sql = "select * from sma_supplier_invoice where id = '$sma_invoice_no' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$sma_invoice_no = $r2['supplier_invoice_no'];
				
				$ipc_date	= date('d-m-Y', strtotime($row['ipc_date']));
				if($ipc_date == '01-01-1970'){
					$ipc_date	='';
				}
				
				$srno = $row['id'];
					$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'IP' order by id desc limit 0,1 ";
						$res  = mysqli_query($con, $s1);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res);
						$reviewed_by		= $r1['reviewed_by'];
						$sl="SELECT * FROM sma_user where id = '$reviewed_by' ";
					
						$r3 = mysqli_query($con, $sl);
						$rw = mysqli_fetch_array($r3);
						$send_to = $rw['username'];
						
						
				$status = $row['status'];
				if($status!='Completed'){
					$status = 'Pending';
				}
						
				$j = $j +1;
				
				$baseurl1 = $baseurl.$modulePath1.'ipc.php?sub=edit&id='.$row["id"];
		?>

				<a href="<?php echo $baseurl . $modulePath1 . "ipc.php?sub=edit&id=". $row['id']?>" title="Edit">
				<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="0%" > <input type="hidden" style="text-align:right;"  value="<?php echo $j?>" ></td>	
					<td width="5%"><?php echo $row['id'];?></td>
					<td width="8%"><?php echo $ipc_date;?></td>
					<td width="20%"><?php echo $comp_name;?></td>
					<td width="15%"><?php echo $sma_vendor_name;?></td>
					<td width="9%"><?php echo $po_number;?></td>
					<td width="8%"><?php echo $sma_invoice_no;?></td>
					<td width="9%"><?php echo $row['sma_po_amount'];?></td>
					<td width="9%"><?php echo $row['sma_invoice_amount'];?></td>
					<td width="8%"><?php echo $row['changed_by'];?></td>
					<td width="8%"><?php echo $status;?></td>
					<td width="8%"><?php echo $send_to;?></td>

				</tr>
			</a>

				<?php } ?>
				
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
//IPC - Approved
if(isset($_POST['sub25'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					
            <!-- /.box-header -->
	<?php
	
			if ($role =='HOD - Account' || $role=='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge'  || $role == 'HOD' || $role == 'CXO' ){
				//$sql="SELECT * from sma_ipc where sma_comp_id in ( $comid ) and approval_status in('Approved') 
				//and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'IP' and status in('Approved')) 
				//or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'IP' and status in('Approved')) or draft_by = '$user' )  order by id desc ";
				$sql = "SELECT * from sma_ipc DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and create_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.sma_comp_id in (  $comid ) and DS.approval_status in('Approved') and DS.del !='Y' order by DS.id desc";
			}
			else {
				$sql = "SELECT * FROM `sma_ipc` where sma_comp_id in ( $comid ) and approval_status = 'Approved' and draft_by = '$user' and del !='Y' order by id desc";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_ipc` where approval_status in('Approved') and del !='Y' order by id desc ";
			}
			
			$modulePath1 = 'ipc/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
		
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                <tr>
					<th>#</th>
        			<th>Sr.No.</th>
					<th>Dated</th>
					<th>Company</th>
					<th>Party</th>
					<th>PO.Number</th>
					<th>Invoice No.</th>
					<th>PO Amount</th>
					<th>Invoice Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
	
				</tr>
                </thead>
                <tbody>
			<?php
			$j = 0;
			while($row = mysqli_fetch_array($result)){
						
				$sma_vendor_id = $row['sma_vendor_id'];
				$sql = "select * from sma_party_mst where id = '$sma_vendor_id' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$sma_vendor_name = $r2['party_name'];
				
				$sma_comp_id = $row['sma_comp_id'];
				$sql = "select * from company where comp_id = '$sma_comp_id' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$comp_name = $r2['comp_name'];
				
				$sma_po_no = $row['sma_po_no'];
				$sql = "select * from sma_purchase_order where id = '$sma_po_no' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$po_number = $r2['po_number'];
				
				$sma_invoice_no = $row['sma_invoice_no'];
				$sql = "select * from sma_supplier_invoice where id = '$sma_invoice_no' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$sma_invoice_no = $r2['supplier_invoice_no'];
				
				$ipc_date	= date('d-m-Y', strtotime($row['ipc_date']));
				if($ipc_date == '01-01-1970'){
					$ipc_date	='';
				}
				
				$j = $j +1;
				
				$baseurl1 = $baseurl.$modulePath1.'ipc.php?sub=edit&id='.$row["id"];
		?>

			<a href="<?php echo $baseurl . $modulePath1 . "ipc.php?sub=edit&id=". $row['id']?>" title="Edit">
				<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="0%" > <input type="hidden" style="text-align:right;"  value="<?php echo $j?>" ></td>	
				
					<td width="5%"><?php echo $row['id'];?></td>
					<td width="8%"><?php echo $ipc_date;?></td>
					<td width="20%"><?php echo $comp_name;?></td>
					<td width="15%"><?php echo $sma_vendor_name;?></td>
					<td width="9%"><?php echo $po_number;?></td>
					<td width="8%"><?php echo $sma_invoice_no;?></td>
					<td width="9%"><?php echo $row['sma_po_amount'];?></td>
					<td width="9%"><?php echo $row['sma_invoice_amount'];?></td>
					<td width="8%"><?php echo $row['changed_by'];?></td>
					<td width="8%"><?php echo $row['status'];?></td>
					<td width="8%"><?php echo $row['approval_status'];?></td>

				</tr>
			</a>

				<?php } ?>
		        
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
//IPC - Rejected
if(isset($_POST['sub26'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
	
			if ($role =='HOD - Account' || $role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge'  || $role == 'HOD' || $role == 'CXO' ){
				$sql="SELECT * from sma_ipc where sma_comp_id in ( $comid ) and approval_status in('Rejected') 
				and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'IP' and status in('Rejected')) 
				or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'IP' and status in('Rejected')) and del !='Y' or draft_by = '$user' ) ";
			}
			else {
				$sql = "SELECT * FROM `sma_ipc` where sma_comp_id in ( $comid ) and approval_status = 'Rejected' and draft_by = '$user' and del !='Y' ";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_ipc` where approval_status = 'Rejected' and   !='Y' ";
			}
			
			$sql .= " order by id desc ";
			
			$modulePath1 = 'ipc/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
				<div class="col-md-12">
			<div class="box"> </div>	
		

              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
        			<th>#</th>
					<th>Sr.No.</th>
					<th>Dated</th>
					<th>Company</th>
					<th>Party</th>
					<th>PO.Number</th>
					<th>Invoice No.</th>
					<th>PO Amount</th>
					<th>Invoice Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
	
				</tr>
                </thead>
                <tbody>
			<?php
			$j = 0;
			while($row = mysqli_fetch_array($result)){
						
				$sma_vendor_id = $row['sma_vendor_id'];
				$sql = "select * from sma_party_mst where id = '$sma_vendor_id' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$sma_vendor_name = $r2['party_name'];
				
				$sma_comp_id = $row['sma_comp_id'];
				$sql = "select * from company where comp_id = '$sma_comp_id' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$comp_name = $r2['comp_name'];
				
				$sma_po_no = $row['sma_po_no'];
				$sql = "select * from sma_purchase_order where id = '$sma_po_no' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$po_number = $r2['po_number'];
				
				$sma_invoice_no = $row['sma_invoice_no'];
				$sql = "select * from sma_supplier_invoice where id = '$sma_invoice_no' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$sma_invoice_no = $r2['supplier_invoice_no'];
				
				$ipc_date	= date('d-m-Y', strtotime($row['ipc_date']));
				if($ipc_date == '01-01-1970'){
					$ipc_date	='';
				}
				
				$j = $j +1;
				
				$baseurl1 = $baseurl.$modulePath1.'ipc.php?sub=edit&id='.$row["id"];
		?>

				<a href="<?php echo $baseurl . $modulePath1 . "ipc.php?sub=edit&id=". $row['id']?>" title="Edit">
				<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="0%" > <input type="hidden" style="text-align:right;"  value="<?php echo $j?>" ></td>	
					<td width="5%"><?php echo $row['id'];?></td>
					<td width="8%"><?php echo $ipc_date;?></td>
					<td width="20%"><?php echo $comp_name;?></td>
					<td width="15%"><?php echo $sma_vendor_name;?></td>
					<td width="9%"><?php echo $po_number;?></td>
					<td width="8%"><?php echo $sma_invoice_no;?></td>
					<td width="9%"><?php echo $row['sma_po_amount'];?></td>
					<td width="9%"><?php echo $row['sma_invoice_amount'];?></td>
					<td width="8%"><?php echo $row['changed_by'];?></td>
					<td width="8%"><?php echo $row['status'];?></td>
					<td width="8%"><?php echo $row['approval_status'];?></td>

				</tr>
			</a>

				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<!--IPC-->

<?php
//Payment
if(isset($_POST['sub27'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
							$doc_type = 'PY';
								$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
									
								if ($role =='HOD - Account' || $role =='Project Manager'){
									
									$sql="SELECT * from payment_header where approval_status in('Pending', 'Verified','Prepared','Submited') and (id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type')) and del !='Y' order by id desc";
									
								}
								else if ($role =='Checker - Account' || $role =='Accountant'){
									$sql = "SELECT * from payment_header DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared','Submited') 
											and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared','Submited') and DS.del !='Y' order by DS.id desc";	
								}
								else if ( $role == 'Checker'){
									//$sql="SELECT * from payment_header where company_id in ($comid) and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') ) and approval_status in('Pending', 'Verified','Prepared','Submited') order by id desc";
									$sql = "SELECT * from payment_header DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared','Submited') 
											and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared','Submited') and DS.del !='Y' order by DS.id desc";	
								//	echo $sql;
								}
								else if ( $role =='Maker'){
									$sql = "SELECT * from payment_header where approval_status in('Pending', 'Verified','Prepared','Submited') and company_id in ($comid) and id in (select payment_hdr_id from payment_details where supp_id in ( select id from sma_supplier_invoice where draft_by = '$user' )) and del !='Y' order by id desc";
									
								}
								else {
									$sql="SELECT * from payment_header where draft_by = '$user' and  approval_status in('Pending', 'Verified','Prepared','Submited') and del !='Y' order by id desc";
								}
										
						
//			if($user == 'Admin'){
//				$sql = "SELECT * FROM `payment_header` where approval_status in('Pending', 'Verified','Prepared') order by id desc ";
//			}
//echo $sql;			
			$modulePath1 = 'payment/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
			
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th></th>
					<th>#</th>
					<th>Paid Date</th>
					<th>Paid via</th>
					<th>Paid To</th>
					<th>UTR.No.</th>
					<th>Dated.</th>
					<th>Supp.No.</td>
					<th style="text-align:right;">Amount Paid</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>				
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;
			while($row = mysqli_fetch_array($result)){
				$cash_bank_name = $row['cash_bank_name'];
				$st_flag 		= $row['st_flag'];
				
				$sql 	= "select * from account_mst where id = '$cash_bank_name' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$cash_bank_name = $r2['account_name'];
				
				$dated = date('d-m-Y', strtotime($row['dated']));
				if($dated =='01-01-1970'){
					$dated = '';
				}
			
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
																
				$rid = $row['id'];
				$sql = "SELECT supplier_invoice_no FROM `payment_details` where payment_hdr_id = '$rid' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$supplier_invoice_no  = $r2['supplier_invoice_no'];
						
				$j =$j +1;
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
							
			?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
				<td width="1%"><input type="hidden" value="<?php echo $j;?>"></td>
				<td width="1%"><?php echo $row['id'];?></td>
				<td width="10%"><?php echo date('d-m-Y', strtotime($row['paid_date']));?></td>
				<td width="10%"><?php echo $cash_bank_name;?></td>
				<td width="12%"><?php echo $party_name;?></td>
				<td width="10%"><?php echo $row['utr_no'];?></td>
				<td width="10%"><?php echo $dated;?></td>
				<td width="10%"><?php echo $supplier_invoice_no;?></td>
				<td width="10%" style="text-align:right;"><?php echo number_format($row['total_amount_paid'],2);?></td>
				<td width="10%"><?php echo $row['changed_by'];?></td>
				<td width="10%"><?php echo $row['status'];?></td>
				<td width="10%"><?php echo $row['approval_status'];?></td>
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
if(isset($_POST['sub28'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
		$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
									
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
			
							if ($role =='HOD - Account'){
									$sql = "SELECT * from payment_header DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
										and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in ( $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') and DS.del !='Y' ";	
								}
							else if ($role =='Checker - Account' || $role =='Accountant'){
									//$sql="SELECT * from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PY')) and  approval_status not in('Completed', 'Draft') and company_id in ($comid) order by id desc";
									$sql = "SELECT * from payment_header DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared','Submited') 
											and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared','Submited') and DS.del !='Y' order by DS.id desc ";
								}
								else if ( $role == 'Checker'){
									$sql="SELECT * from payment_header where company_id in ($comid) and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PY')) and  approval_status not in('Completed', 'Draft') and DS.del !='Y' order by id desc";
								//	echo $sql;
								}
								else if ( $role =='Maker'){
									$sql = "SELECT * FROM `payment_header` where company_id in ($comid) and status not in('Completed', 'Draft') and draft_by = '$user' and del !='Y' order by id desc";
									//echo $sql;
								}
								else {
									$sql="SELECT * from payment_header where draft_by = '$user' and  approval_status not in('Completed', 'Draft') and del !='Y' order by id desc";
								}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `payment_header` where status not in('Completed', 'Draft') and del !='Y' order by id desc ";
			}
			
//echo $sql;
			
			$modulePath1 = 'payment/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
		
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>#</th>
					<th>Paid Date</th>
					<th>Paid via</th>
					<th>Paid To</th>
					<th>UTR.No.</th>
					<th>Dated.</th>
					<th>Supp.No.</td>
					<th style="text-align:right;">Amount Paid</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>To</th>
					
				</tr>
                </thead>
                <tbody>
			<?php
				$j =0;
				while($row = mysqli_fetch_array($result)){
						$cash_bank_name = $row['cash_bank_name'];
						$sql 	= "select * from account_mst where id = '$cash_bank_name' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$cash_bank_name = $r2['account_name'];
						
						$dated = date('d-m-Y', strtotime($row['dated']));
						if($dated =='01-01-1970'){
							$dated = '';
						}
					
						$deduction_amt		= $row["tds_amount"];
						$total_amount_paid	= $row['total_amount_paid'];
						$actual_paid		= $total_amount_paid - $deduction_amt;
						
						$status = $row['status'];
						if($status!='Completed'){
							$status = 'Pending';
						}
						
						$srno = $row['id'];
						$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'PY' order by id desc limit 0,1 ";
					//echo $s1;	
						$res  = mysqli_query($con, $s1);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res);
						$reviewed_by		= $r1['reviewed_by'];
						$sl="SELECT * FROM sma_user where id = '$reviewed_by' ";
					
						$r3 = mysqli_query($con, $sl);
						$rw = mysqli_fetch_array($r3);
						$send_to = $rw['username'];
						
						$paid_to = $row['paid_to'];
						$sql = "SELECT party_name FROM `sma_party_mst` where id = '$paid_to' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$party_name  = $r2['party_name'];
						
						$rid = $row['id'];
						$sql = "SELECT supplier_invoice_no FROM `payment_details` where payment_hdr_id = '$rid' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$supplier_invoice_no  = $r2['supplier_invoice_no'];
						
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $j;?>"></td>
					<td width="1%"><?php echo $row['id'];?></td>
					<td width="10%"><?php echo date('d-m-Y', strtotime($row['paid_date']));?></td>
					<td width="10%"><?php echo $cash_bank_name;?></td>
					<td width="12%"><?php echo $party_name;?></td>
					<td width="10%"><?php echo $row['utr_no'];?></td>
					<td width="10%"><?php echo $dated;?></td>
					<td width="10%"><?php echo $supplier_invoice_no;?></td>
					<td width="10%" style="text-align:right;"><?php echo number_format($actual_paid,2);?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $status;?></td>
					<td width="10%"><?php echo $send_to;?></td>
				
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
if(isset($_POST['sub29'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
		$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
		
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					
            <!-- /.box-header -->
	<?php
							if ($role =='HOD - Account'){
									$sql="SELECT * from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') ) and  approval_status in('Approved') and del !='Y' order by id desc";
								}
								else if ($role =='Checker - Account' || $role =='Accountant'){
									$sql="SELECT * from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PY')) and  approval_status in('Approved') or draft_by = '$user' and del !='Y' order by id desc";
								}
								else if ( $role == 'Checker'){
									//$sql="SELECT * from payment_header where company_id in ($comid) and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PY')) and  approval_status in('Approved') order by id desc";
									$sql = "SELECT * from payment_header DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Approved') 
											and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Approved') and DS.del !='Y' order by DS.id desc ";	
								//	echo $sql;
								}
								else if ( $role =='Maker'){
									$sql = "SELECT * from payment_header where company_id in ($comid) and id in (select payment_hdr_id from payment_details where supp_id in ( select id from sma_supplier_invoice where draft_by = '$user' )) and approval_status in('Approved') and DS.del !='Y' order by id desc";
									//echo $sql;
								}
								else {
									$sql="SELECT * from payment_header where draft_by = '$user' and  approval_status in('Approved') and del !='Y' order by id desc";
								}
								
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `payment_header` where approval_status in('Approved') and del !='Y' order by id desc ";
			}
			
			$modulePath1 = 'payment/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
		
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>#</th>
					<th>Paid Date</th>
					<th>Paid via</th>
					<th>Paid To</th>
					<th>UTR.No.</th>
					<th>Dated.</th>
					<th>Supp.No.</td>
					<th style="text-align:right;">Amount Paid</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
					
				</tr>
                </thead>
                <tbody>
			<?php
				$j =0;
					while($row = mysqli_fetch_array($result)){
						$cash_bank_name = $row['cash_bank_name'];
						$sql 	= "select * from account_mst where id = '$cash_bank_name' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$cash_bank_name = $r2['account_name'];
						
						$dated = date('d-m-Y', strtotime($row['dated']));
						if($dated =='01-01-1970'){
							$dated = '';
						}
					
						$paid_to = $row['paid_to'];
						$sql = "SELECT party_name FROM `sma_party_mst` where id = '$paid_to' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$party_name  = $r2['party_name'];
						
						$deduction_amt		= $row["tds_amount"];
						$total_amount_paid	= $row['total_amount_paid'];
						$actual_paid		= $total_amount_paid - $deduction_amt;
						
						$rid = $row['id'];
						$sql = "SELECT supplier_invoice_no FROM `payment_details` where payment_hdr_id = '$rid' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$supplier_invoice_no  = $r2['supplier_invoice_no'];
						
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $j;?>"></td>
					<td width="1%"><?php echo $row['id'];?></td>
					<td width="10%"><?php echo date('d-m-Y', strtotime($row['paid_date']));?></td>
					<td width="10%"><?php echo $cash_bank_name;?></td>
					<td width="12%"><?php echo $party_name;?></td>
					<td width="10%"><?php echo $row['utr_no'];?></td>
					<td width="10%"><?php echo $dated;?></td>
					<td width="10%"><?php echo $supplier_invoice_no;?></td>
					<td width="10%" style="text-align:right;"><?php echo number_format($actual_paid,2);?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $row['approval_status'];?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
				
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
if(isset($_POST['sub30'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];

		$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';		
		
		
?>

		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
		
							if ($role =='HOD - Account'){
									$sql="SELECT * from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') ) and  approval_status in('Rejected') and del !='Y'  or draft_by = '$user' order by id desc";
								}
								else if ($role =='Checker - Account' || $role =='Accountant'){
									//$sql="SELECT * from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') ) and  approval_status in('Rejected') or draft_by = '$user' order by id desc";
									$sql = "SELECT * from payment_header DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and status in('Rejected') 
											and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Rejected') and DS.del !='Y' order by DS.id desc";
								}
								else if ( $role == 'Checker' ){
									//$sql="SELECT * from payment_header where  company_id in ($comid) and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY')) and approval_status in('Rejected') order by id desc";
									$sql = "SELECT * from payment_header DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Rejected') 
											and id in ($id_var) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Rejected') and DS.del !='Y' order by DS.id desc";
								//	echo $sql;
								}
								else if ( $role =='Maker'){
									$sql = "SELECT * from payment_header where company_id in ($comid) and id in (select payment_hdr_id from payment_details where supp_id in ( select id from sma_supplier_invoice where draft_by = '$user' )) and approval_status in('Rejected') and del !='Y' order by id desc";
									//echo $sql;
								}
								else {
									$sql="SELECT * from payment_header where draft_by = '$user' and  approval_status in('Rejected') and del !='Y' order by id desc";
								}
																			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `payment_header` where approval_status in('Rejected') and del !='Y' order by id desc ";
			}
			
			$modulePath1 = 'payment/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
		
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>#</th>
					<th>Paid Date</th>
					<th>Paid via</th>
					<th>Paid To</th>
					<th>UTR.No.</th>
					<th>Dated.</th>
					<th>Supp.No.</td>
					<th style="text-align:right;">Amount Paid</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
				
				</tr>
                </thead>
                <tbody>
			<?php
				$j =0;
					while($row = mysqli_fetch_array($result)){
						$cash_bank_name = $row['cash_bank_name'];
						$sql 	= "select * from account_mst where id = '$cash_bank_name' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$cash_bank_name = $r2['account_name'];
						
						$dated = date('d-m-Y', strtotime($row['dated']));
						if($dated =='01-01-1970'){
							$dated = '';
						}
					
						$deduction_amt		= $row["tds_amount"];
						$total_amount_paid	= $row['total_amount_paid'];
						$actual_paid		= $total_amount_paid - $deduction_amt;
							
						$paid_to = $row['paid_to'];
						$sql = "SELECT party_name FROM `sma_party_mst` where id = '$paid_to' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$party_name  = $r2['party_name'];
						
						$rid = $row['id'];
						$sql = "SELECT supplier_invoice_no FROM `payment_details` where payment_hdr_id = '$rid' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$supplier_invoice_no  = $r2['supplier_invoice_no'];
								
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $j;?>"></td>
					<td width="1%"><?php echo $row['id'];?></td>
					<td width="10%"><?php echo date('d-m-Y', strtotime($row['paid_date']));?></td>
					<td width="10%"><?php echo $cash_bank_name;?></td>
					<td width="12%"><?php echo $party_name;?></td>
					<td width="10%"><?php echo $row['utr_no'];?></td>
					<td width="10%"><?php echo $dated;?></td>
					<td width="10%"><?php echo $supplier_invoice_no;?></td>
					<td width="10%" style="text-align:right;"><?php echo number_format($actual_paid,2);?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $row['approval_status'];?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
				
			</tr>
		</a>
				<?php } ?>
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<!--Payment-->

<?php
//Travel Request
if(isset($_POST['sub31'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_traval_approval where emp_id = '$usrid' and status != 'Withdraw' and del !='Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
//My Pending
								if ( $cnt_my>0 ){
									$sql="SELECT * from sma_traval_approval where emp_id = '$usrid' and approval_status in('Pending', 'Prepared') and status != 'Withdraw' and id < 1";  //not pending listing for users
								}
								
								if( $department=='12' || $role=='accountant' || $role == 'CXO' || $role == 'Project Manager' ){
									
									$sql = " SELECT * from sma_traval_approval where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TA' and status in ('Pending') and reviewed_by = '$usrid' ) and approval_status in( 'Pending') ";
									
								}
								
								if( $role =='Maker' || $role == 'Checker' ){
									
									$sql = " SELECT * from sma_traval_approval where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TA' and status in ('Pending', 'Prepared') and reviewed_by = '$usrid' ) and approval_status in( 'Pending', 'Prepared') ";
									
								}

								if ( $user=='Admin' ){
		
									$sql = " SELECT * from sma_traval_approval where approval_status in('Pending', 'Prepared') and status != 'Withdraw' ";
									
								}
				$sql .= " and del !='Y' ";
				$sql .=" order by id desc";
			 
	//echo $sql. ' '. $department. ' ' . $role;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th></th>
					<th>SrNo.</th>
					<th>Name</th>
					<th>Location From</th>
					<th>Date From</th>
					<th>Location To</th>
					<th>Date To</th>
					<th>Company</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
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
		
		$baseurl1 = $baseurl.$modulePath1.'traval_app.php?sub=edit&id='.$row["id"];
?>

	<a href="<?php echo $baseurl . $modulePath1 . "traval_app.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%" style="text-align:right;"><?php echo $row['id'];?></td>
		<td width="10%"><?php echo $emp_name;?></td>
		<td width="10%"><?php echo $row['traval_from'];?></td>
		<td width="10%"><?php echo $start_date;?></td>
		<td width="13%"><?php echo $row['traval_to'];?></td>
		<td width="10%"><?php echo $end_date;?></td>
		<td width="04%"><?php echo $comp_code;?></td>
		<td width="10%"><?php echo $row['advance_amount'];?></td>
		<td width="08%"><?php echo $row['changed_by'];?></td>
		<td width="08%"><?php echo $row['status'];?></td>
		<td width="08%"><?php echo $row['approval_status'];?></td>

    </tr>
	</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
if(isset($_POST['sub32'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];
								$sql = "SELECT count(*) as cnt from sma_traval_approval where emp_id = '$usrid' and status != 'Withdraw' and del !='Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
								
								if ($cnt_my>0){
									$sql="SELECT * from sma_traval_approval where emp_id = '$usrid' and approval_status in('Prepared') and status != 'Withdraw' ";
								}
								
								if($department=='12' || $department=='13' || $department=='14' || $role == 'CXO' || $role == 'Project Manager' ){
									
									$sql = " SELECT * from sma_traval_approval where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TA' and status = 'Prepared' and create_by = '$usrid' ) and approval_status = 'Prepared' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin'  ){
		
									$sql = " SELECT * from sma_traval_approval where status != 'Withdraw' and approval_status in ('Prepared') ";
									
								}
								
				$sql .= " and del !='Y' ";
				$sql .=" order by id desc";
			 
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th></th>
					<th>SrNo.</th>
					<th>Name</th>
					<th>Location From</th>
					<th>Date From</th>
					<th>Location To</th>
					<th>Date To</th>
					<th>Company</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
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
		
		$baseurl1 = $baseurl.$modulePath1.'traval_app.php?sub=edit&id='.$row["id"];
?>

	<a href="<?php echo $baseurl . $modulePath1 . "traval_app.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%" style="text-align:right;"><?php echo $row['id'];?></td>
		<td width="10%"><?php echo $emp_name;?></td>
		<td width="10%"><?php echo $row['traval_from'];?></td>
		<td width="10%"><?php echo $start_date;?></td>
		<td width="13%"><?php echo $row['traval_to'];?></td>
		<td width="10%"><?php echo $end_date;?></td>
		<td width="04%"><?php echo $comp_code;?></td>
		<td width="10%"><?php echo $row['advance_amount'];?></td>
		<td width="08%"><?php echo $row['changed_by'];?></td>
		<td width="08%"><?php echo $row['status'];?></td>
		<td width="08%"><?php echo $row['approval_status'];?></td>

    </tr>
	</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
if(isset($_POST['sub33'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_traval_approval where emp_id = '$usrid' and status != 'Withdraw' and del !='Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];

								if ($cnt_my>0){
									$sql="SELECT * from sma_traval_approval where emp_id = '$usrid' and approval_status in('Approved') and status != 'Withdraw' ";
								}
							
								if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_traval_approval where approval_status = 'Approved' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT * from sma_traval_approval where status != 'Withdraw' and approval_status in ('Approved') ";
									
								}
				
				$sql .= " and del !='Y' ";

				$sql .=" order by id desc";
			 
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th></th>
					<th>SrNo.</th>
					<th>Name</th>
					<th>Location From</th>
					<th>Date From</th>
					<th>Location To</th>
					<th>Date To</th>
					<th>Company</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
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
		
		$baseurl1 = $baseurl.$modulePath1.'traval_app.php?sub=edit&id='.$row["id"];
?>

	<a href="<?php echo $baseurl . $modulePath1 . "traval_app.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%" style="text-align:right;"><?php echo $row['id'];?></td>
		<td width="10%"><?php echo $emp_name;?></td>
		<td width="10%"><?php echo $row['traval_from'];?></td>
		<td width="10%"><?php echo $start_date;?></td>
		<td width="13%"><?php echo $row['traval_to'];?></td>
		<td width="10%"><?php echo $end_date;?></td>
		<td width="04%"><?php echo $comp_code;?></td>
		<td width="10%"><?php echo $row['advance_amount'];?></td>
		<td width="08%"><?php echo $row['changed_by'];?></td>
		<td width="08%"><?php echo $row['status'];?></td>
		<td width="08%"><?php echo $row['approval_status'];?></td>

    </tr>
	</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
if(isset($_POST['sub34'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>

		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_traval_approval where emp_id = '$usrid' and status != 'Withdraw' and del != 'y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];

								
								if ($cnt_my>0){
									$sql="SELECT * from sma_traval_approval where emp_id = '$usrid' and approval_status in('Rejected') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_traval_approval where approval_status = 'Rejected' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin'  ){
		
									$sql = " SELECT * from sma_traval_approval where status != 'Withdraw' and status in ('Rejected') ";
									
								}
								
				$sql .= " and del !='Y' ";
				$sql .=" order by id desc";
			 
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th></th>
					<th>SrNo.</th>
					<th>Name</th>
					<th>Location From</th>
					<th>Date From</th>
					<th>Location To</th>
					<th>Date To</th>
					<th>Company</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
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
		
		$baseurl1 = $baseurl.$modulePath1.'traval_app.php?sub=edit&id='.$row["id"];
?>

	<a href="<?php echo $baseurl . $modulePath1 . "traval_app.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%" style="text-align:right;"><?php echo $row['id'];?></td>
		<td width="10%"><?php echo $emp_name;?></td>
		<td width="10%"><?php echo $row['traval_from'];?></td>
		<td width="10%"><?php echo $start_date;?></td>
		<td width="13%"><?php echo $row['traval_to'];?></td>
		<td width="10%"><?php echo $end_date;?></td>
		<td width="04%"><?php echo $comp_code;?></td>
		<td width="10%"><?php echo $row['advance_amount'];?></td>
		<td width="08%"><?php echo $row['changed_by'];?></td>
		<td width="08%"><?php echo $row['status'];?></td>
		<td width="08%"><?php echo $row['approval_status'];?></td>

    </tr>
	</a>
				<?php } ?>
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<!--Travel Request-->



<?php
//Travel Expenses
if(isset($_POST['sub35'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and status != 'Withdraw' and del != 'Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
 								
								if ($cnt_my>0){
									$sql="SELECT * from sma_travel_expenses where emp_id = '$usrid'  and exp_type = 'T' and approval_status in('Pending') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TE' and status in ('Pending') and reviewed_by = '$usrid' )  and exp_type = 'T' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT *  from sma_travel_expenses where approval_status in('Pending') and exp_type = 'T' and status != 'Withdraw' ";
									
								}	

						$sql .= " and del !='Y' ";
						$sql .=" order by id desc";
								
						//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt = $r1['cnt'];
								}
			 
			//echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th></th>
					<th>SrNo.</th>
					<th>Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
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
		
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		
		if($dated=='01-01-1970'){ $dated='';}
		
		$baseurl1 = $baseurl.$modulePath1.'travel_expence.php?sub=edit&id='.$row["id"];
?>
<a href="<?php echo $baseurl . $modulePath1 . "travel_expence.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
	<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="15%"><?php echo $emp_name;?></td>
		<td width="15%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%" style="text-align;right;"><?php echo $row['advance_amount'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>

    </tr>
	</a>
				<?php } ?>
				
                </tbody>
				</table>
				
			  
            </div>
		
<?php		
}	
?>

<?php
if(isset($_POST['sub36'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and status != 'Withdraw' and del != 'Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
//All Pending								
								if ($cnt_my>0){
									$sql="SELECT * from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and approval_status in('Pending') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TE' and status = 'Prepared' and create_by = '$usrid' ) and approval_status = 'Prepared' and exp_type = 'T' and status != 'Withdraw' ";
									
								}
								else {
									
									$sql = " SELECT * from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TE' and status = 'Prepared' and create_by = '$usrid' ) and approval_status = 'Prepared' and exp_type = 'T' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'T' and approval_status in ('Prepared') ";
									
								}

				$sql .= " and del !='Y' ";
				$sql .=" order by id desc";

				//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt = $r1['cnt'];
								}
											 
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th></th>
					<th>SrNo.</th>
					<th>Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
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
		
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		
		if($dated=='01-01-1970'){ $dated='';}
		
		$baseurl1 = $baseurl.$modulePath1.'travel_expence.php?sub=edit&id='.$row["id"];
?>
<a href="<?php echo $baseurl . $modulePath1 . "travel_expence.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
	<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="15%"><?php echo $emp_name;?></td>
		<td width="15%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%" style="text-align;right;"><?php echo $row['advance_amount'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>

    </tr>
	</a>
				<?php } ?>
				
                </tbody>
				</table>
				
            </div>
		
<?php		
}	
?>


<?php
if(isset($_POST['sub37'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and status != 'Withdraw' and del != 'Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
								
//Approved								
								if ($cnt_my>0){
									$sql="SELECT * from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and approval_status in('Approved') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'T' and approval_status in('Approved') ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'T' and approval_status in ('Approved') ";
									
								}	

						$sql .= " and del !='Y' ";		
						$sql .=" order by id desc";
								
//							echo $cnt_my. ' >>><<< ' .$sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$acnt = $r1['cnt'];
								}
			 
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th></th>
					<th>SrNo.</th>
					<th>Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
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
		
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		
		if($dated=='01-01-1970'){ $dated='';}
		
		$baseurl1 = $baseurl.$modulePath1.'travel_expence.php?sub=edit&id='.$row["id"];
?>
<a href="<?php echo $baseurl . $modulePath1 . "travel_expence.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
	<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="15%"><?php echo $emp_name;?></td>
		<td width="15%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%" style="text-align;right;"><?php echo $row['advance_amount'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>

    </tr>
	</a>
				<?php } ?>
				
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
if(isset($_POST['sub38'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>

		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and status != 'Withdraw'  and del != 'Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
								
//Rejected								
								if ($cnt_my>0){
									$sql="SELECT * from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and approval_status in('Rejected') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'T' and approval_status in('Rejected') ";
									
								}

								if ($user=='Admin'){
		
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'T' and approval_status in ('Rejected') ";
									
								}
								
				$sql .= " and del !='Y' ";
				$sql .=" order by id desc";
			 
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
					<th>SrNo.</th>
					<th>Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
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
		
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		
		$baseurl1 	= $baseurl.$modulePath1.'travel_expence.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "travel_expence.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="15%"><?php echo $username;?></td>
		<td width="15%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%"><?php echo $row['advance_amount'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
		<td width="5%" style="text-align:right;">
		<a href="travel_expence.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		</td>
    </tr>
	</a>
	<?php }?>
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<!--Travel Expenses-->



<?php
//Regular Expense
if(isset($_POST['sub39'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and status != 'Withdraw' and del !='Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
//My Pending								
								//if ($cnt_my>0){
								//	$sql="SELECT * from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and approval_status in('Pending') and status != 'Withdraw' ";
								//}
								//else 
								if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'RE' and status in ('Pending') and reviewed_by = '$usrid' ) and approval_status in('Pending') and exp_type = 'R' and status != 'Withdraw' ";
									
								}
								else {
									
									$sql = " SELECT * from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'RE' and status in ('Pending') and reviewed_by = '$usrid' )  and approval_status in('Pending') and exp_type = 'R' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin'  ){
		
									$sql = " SELECT *  from sma_travel_expenses where approval_status in('Pending') and exp_type = 'R' and status != 'Withdraw' ";
									
								}	
				
						$sql .= " and del !='Y' ";
						$sql .=" order by id desc";
									
						//echo $sql;
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt = $r1['cnt'];
								}
				
//echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th></th>
					<th>SrNo.</th>
					<th>Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
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
		
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		
		if($dated=='01-01-1970'){ $dated='';}
		
		$baseurl1 = $baseurl.$modulePath1.'regular_expense.php?sub=edit&id='.$row["id"];
?>
<a href="<?php echo $baseurl . $modulePath1 . "regular_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
	<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="15%"><?php echo $emp_name;?></td>
		<td width="15%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%" style="text-align;right;"><?php echo $row['advance_amount'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>

    </tr>
	</a>
				<?php } ?>
				
                
                </tbody>
              </table>
			  
			  
            </div>
		
<?php		
}	
?>

<?php
if(isset($_POST['sub40'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];

								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and status != 'Withdraw' and del != 'Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
								
//All Pending								
								if ($cnt_my>0){
									$sql="SELECT * from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and approval_status in('Pending') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'RE' and status = 'Prepared' and create_by = '$usrid' ) and approval_status = 'Prepared' and exp_type = 'R' and status != 'Withdraw' ";
									
								}
								else {
									
									$sql = " SELECT * from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'RE' and status = 'Prepared' and create_by = '$usrid' ) and approval_status = 'Prepared' and exp_type = 'R' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'R' and approval_status in ('Prepared', 'Pending' ) ";
									
								}

								$sql .= " and del !='Y' ";
								$sql .=" order by id desc";
						//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt = $r1['cnt'];
								}
								
			 
			//echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
               <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th></th>
					<th>SrNo.</th>
					<th>Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
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
		
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		
		if($dated=='01-01-1970'){ $dated='';}
		
		$baseurl1 = $baseurl.$modulePath1.'regular_expense.php?sub=edit&id='.$row["id"];
?>
<a href="<?php echo $baseurl . $modulePath1 . "regular_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
	<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="15%"><?php echo $emp_name;?></td>
		<td width="15%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%" style="text-align;right;"><?php echo $row['advance_amount'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>

    </tr>
	</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
if(isset($_POST['sub41'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];

								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and status != 'Withdraw' and del != 'Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
								
//Approved								
								if ($cnt_my>0){
									$sql="SELECT * from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and approval_status in('Approved') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'R' and approval_status in('Approved') ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'R' and approval_status in ('Approved') ";
									
								}	

								$sql .= " and del !='Y' ";				
								$sql .=" order by id desc";
			 
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$acnt = $r1['cnt'];
								}
								
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th></th>
					<th>SrNo.</th>
					<th>Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
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
		
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		
		if($dated=='01-01-1970'){ $dated='';}
		
		$baseurl1 = $baseurl.$modulePath1.'regular_expense.php?sub=edit&id='.$row["id"];
?>
<a href="<?php echo $baseurl . $modulePath1 . "regular_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
	<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="15%"><?php echo $emp_name;?></td>
		<td width="15%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%" style="text-align;right;"><?php echo $row['advance_amount'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>

    </tr>
	</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
if(isset($_POST['sub42'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>

		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];

								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and status != 'Withdraw'  and del!='Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
								
//Rejected								
								if ($cnt_my>0){
									$sql="SELECT * from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and approval_status in('Rejected') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'R' and approval_status in('Rejected') ";
									
								}

								if ($user=='Admin'  ){
		
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'R' and approval_status in ('Rejected') ";
									
								}
								
				$sql .= " and del !='Y' ";				
				$sql .=" order by id desc";
			 
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
					<th>SrNo.</th>
					<th>Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
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
		
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		
		$baseurl1 	= $baseurl.$modulePath1.'regular_expense.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "regular_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="15%"><?php echo $username;?></td>
		<td width="15%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%"><?php echo $row['advance_amount'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
		<td width="5%" style="text-align:right;">
		<a href="travel_expence.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		</td>
    </tr>
	</a>
	<?php }?>
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<!--Regular Expense-->


<?php

//Operating Expense
if(isset($_POST['sub43'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php

	
								$department = $_SESSION['department'];
								
								//$sql = "SELECT count(*) as cnt from sma_travel_expenses where draft_by = '$user' and exp_type = 'C' and status != 'Withdraw' ";
								//$result = mysqli_query($con, $sql);
								//echo mysqli_error($con);
								//$r1  = mysqli_fetch_array($result);
								//$cnt_my = $r1['cnt'];
//My Pending								
								//if ($cnt_my>0){
								//	$sql="SELECT * from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'C' and approval_status in('Pending') and status != 'Withdraw' ";
								//}
								//else 
								if($department=='12' || $department=='13' || $department=='14'){
									$cnt_my=1;		
									$sql = " SELECT * from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'CE' and status in ('Pending') and reviewed_by = '$usrid' ) and exp_type = 'C' and status != 'Withdraw' and approval_status = 'Pending'";
									
								}
								else {
									$cnt_my=1;		
									$sql = " SELECT * from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'CE' and status in ('Pending') and reviewed_by = '$usrid' ) and exp_type = 'C' and status != 'Withdraw' and approval_status = 'Pending'";
									
								}

								if ($user=='Admin' ){
									$cnt_my=1;
									$sql = " SELECT *  from sma_travel_expenses where approval_status = 'Pending' and exp_type = 'C' and status != 'Withdraw' ";
									
								}	
								
								$sql .= " and del !='Y' ";				
								$sql .=" order by id desc";
			 
			//	echo $sql;
						
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt = $r1['cnt'];
								}


				//$sql .=" order by id desc";
//			 echo $cnt_my;
				//if($cnt_my==0){$sql = '';}
				
	//		 echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
					<th>SrNo.</th>
					<th>Party Name </th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		if($cnt_my==0){continue;}
		
		$re_id = $row["id"];
		$sql  = "SELECT sum(amount) as amount FROM `sma_expenses` where exp_type = 'C' and approval_ref_no = '$re_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$amount		= $r1['amount'];
		
		$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		//$comp_name 		= $r1['comp_name'];
		$comp_name 		= $r1['comp_code'];

		$emp_id = $row['emp_id'];
		$sql="SELECT * from sma_party_mst where id = '$emp_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$username		= $r1['party_name'];
		
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		
		$baseurl1 	= $baseurl.$modulePath1.'company_expense.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "company_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="25%"><?php echo $username;?></td>
		<td width="05%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%"><?php echo $amount;?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
    </tr>
	</a>
	<?php }?>

                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
if(isset($_POST['sub44'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];

								$sql = "SELECT count(*) as cnt from sma_travel_expenses where draft_by = '$user' and exp_type = 'C' and status != 'Withdraw' and del !='Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
								
//All Pending								
								
								if ($cnt_my>0){
									$sql="SELECT * from sma_travel_expenses where draft_by = '$user' and exp_type = 'C' and approval_status in('Pending') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where exp_type = 'C' and id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'CE' and status = 'Prepared' and create_by = '$usrid' ) and approval_status = 'Prepared' and status != 'Withdraw' ";
									
								}
								else {
									
									$sql = " SELECT * from sma_travel_expenses where exp_type = 'C' and id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'CE' and status = 'Prepared' and create_by = '$usrid' ) and approval_status = 'Prepared' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin' ){
		
									//$sql = " SELECT * from sma_travel_expenses where exp_type = 'C' and status != 'Withdraw' and status in ('Prepared') ";
									$sql = " SELECT * from sma_travel_expenses where exp_type = 'C' and status != 'Withdraw' and status = 'Prepared' ";
								}
								
								$sql .= " and del !='Y' ";
								$sql .=" order by id desc";
			 

								
//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt = $r1['cnt'];
								}
								

				$sql .=" order by id desc";
			 
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
					<th>SrNo.</th>
					<th>Party Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
				$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		//$comp_name 		= $r1['comp_name'];
		$comp_name 		= $r1['comp_code'];

		
		$re_id = $row["id"];
		$sql  = "SELECT sum(amount) as amount FROM `sma_expenses` where exp_type = 'C' and approval_ref_no = '$re_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$amount		= $r1['amount'];
		
		
		$emp_id = $row['emp_id'];
		$sql="SELECT * from sma_party_mst where id = '$emp_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$username		= $r1['party_name'];
		
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		
		$baseurl1 	= $baseurl.$modulePath1.'company_expense.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "company_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="25%"><?php echo $username;?></td>
		<td width="05%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%"><?php echo $amount;?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
    </tr>
	</a>
	<?php }?>

                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
if(isset($_POST['sub45'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];

								$sql = "SELECT count(*) as cnt from sma_travel_expenses where draft_by = '$user' and exp_type = 'C' and status != 'Withdraw' and del != 'Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
								
//Approved								
								if ($cnt_my>0){
									$sql="SELECT * from sma_travel_expenses where draft_by = '$user' and exp_type = 'C' and approval_status in('Approved') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'C' and approval_status in('Approved') ";
									
								}
								else {
									
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'C' and approval_status in('Approved') ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'C' and approval_status in ('Approved') ";
									
								}	
															
							$sql .= " and del !='Y' ";				
							$sql .= " order by id desc";
								
							//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$acnt = $r1['cnt'];
								}
								
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
					<th>SrNo.</th>
					<th>Party Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
				$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		//$comp_name 		= $r1['comp_name'];
		$comp_name 		= $r1['comp_code'];

		$re_id = $row["id"];
		$sql  = "SELECT sum(amount) as amount FROM `sma_expenses` where exp_type = 'C' and approval_ref_no = '$re_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$amount		= $r1['amount'];
		
		$emp_id = $row['emp_id'];
		$sql="SELECT * from sma_party_mst where id = '$emp_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$username		= $r1['party_name'];
		
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		
		$baseurl1 	= $baseurl.$modulePath1.'company_expense.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "company_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="25%"><?php echo $username;?></td>
		<td width="05%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%"><?php echo $amount;?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
    </tr>
	</a>
	<?php }?>

                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
if(isset($_POST['sub46'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>

		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];

								$sql = "SELECT count(*) as cnt from sma_travel_expenses where draft_by = '$user' and exp_type = 'C' and status != 'Withdraw' and del != 'Y' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
								
//Rejected								
								if ($cnt_my>0){
									$sql="SELECT * from sma_travel_expenses where draft_by = '$user' and exp_type = 'C' and approval_status in('Rejected') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'C' and approval_status in('Rejected') ";
									
								}
								else {
									
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'C' and approval_status in('Rejected') ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'C' and approval_status in ('Rejected') ";
									
								}
				
				$sql .= " and del !='Y' ";				
				$sql .=" order by id desc";
			 
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
					<th>SrNo.</th>
					<th>Party Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		//$comp_name 		= $r1['comp_name'];
		$comp_name 		= $r1['comp_code'];

		$re_id = $row["id"];
		$sql  = "SELECT sum(amount) as amount FROM `sma_expenses` where exp_type = 'C' and approval_ref_no = '$re_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$amount		= $r1['amount'];
		
		$emp_id = $row['emp_id'];
		$sql="SELECT * from sma_party_mst where id = '$emp_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$username		= $r1['party_name'];
		
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		
		$baseurl1 	= $baseurl.$modulePath1.'company_expense.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "company_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="25%"><?php echo $username;?></td>
		<td width="05%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%"><?php echo $amount;?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
    </tr>
	</a>
	<?php }?>
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>
<!--Operating Expense-->


<?php

// Petty Cash 
//Pending Start
if(isset($_POST['sub47'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>

		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">

<?php
						
							$doc_type	= 'PC';
							$pcnt_py = 0;
					//echo $role. "<BR>";
								if ( $role == 'Checker - Account' || $role =='Accountant' || $role == 'Checker' || $role == 'Project Manager' || $role == 'HOD - Account' ){
									$id_var = '';
									$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_type = '$doc_type' group by doc_id";
									$qry = mysqli_query($con, $sql);
									while( $rs = mysqli_fetch_array($qry) ){
										$id_var .= $rs['idd'].',';
									}
									$id_var .= '0';
								}
								
							$pcnt_pc = 0;
							$id_var1 = '0';
							$id_var2 = '0';
					//echo $role. "<BR>";
								if ( $role == 'Checker - Account' || $role =='Accountant' || $role == 'Checker' || $role == 'Project Manager' || $role == 'HOD - Account' || $role == 'Project Incharge' ){
									$id_var1 = '';
									$sql = " SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' ";
									//echo $sql. "<BR>";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var1 .= $rs['doc_id'].',';
									}
									$id_var1 .= '0';
									
									/* $id_var2 = '';
									$sql = " SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' ";
									//echo $sql. "<BR>";
									$qry = mysqli_query($con,$sql);
									while ( $rs = mysqli_fetch_array($qry)){
										$id_var2 .= $rs['doc_id'].',';
									} */
									$id_var2 .= '0';
									$sql = " SELECT * from sma_pettycash where approval_status in ( 'Pending', 'Verified', 'Prepared', 'Submited' ) and ( id in ( $id_var1 ) or id in ( $id_var2 ) ) and del!='Y' ";
									
								}
								
								if( $user=='Admin'  ){
									$sql = " SELECT * from sma_pettycash where approval_status in ( 'Pending', 'Verified', 'Prepared', 'Submited' )  and del!='Y' ";
								}	
								
						$sql .= ' order by id desc ';		
						//echo $sql."<BR>";	
						
		$modulePath1 = 'petty/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
					<th>SrNo.</th>
					<th>Company</th>
					<th>Location</th>
					<th>Date</th>
					<th>Type</th>
					<th>Total Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$comp_name 	= $r1['comp_name'];
		
		$location_id  = $row['location_id'];
		$sql  = "SELECT * from sma_location where id = '$location_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$loc_name 	= $r1['loc_name'];
		
		$trans_type = $row['trans_type'];	
		$pc_id 		= $row["id"];
		$amount = 0;
		$sql  = "SELECT sum(amount) as amount, trans_type FROM `sma_pettycash_exp` where  approval_ref_no = '$pc_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r1 = mysqli_fetch_array($res1)){
			if($trans_type =='R'){
				$amount		-= $r1['amount'];
				$changed_by = $row['draft_by'];
			}
			else if($trans_type =='P'){
				$amount		+= $r1['amount'];
				$changed_by = $row['changed_by'];
			}
		}
		
		
		if($amount<0){
			$amount = $amount * -1;
		}	
			 
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		if( $dated=='01-01-1970' ){ $dated='';}
		
			$styl  = '';
			$styl2 = '';
			$del   = $row['del'];
			if($del =='Y'){
						
				$styl = "style='bgcolor:powderblue;color:red;' ";
				$styl2 = "bgcolor:powderblue;color:red; ";
							
			}
		
			$status = $row['status'];
			if($searchfu=='U'){
				$status = "UnPaid";
			}
			
		$baseurl1 	= $baseurl.$modulePath1.'pettycash_expense.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "pettycash_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"<?php echo $styl; ?>><?php echo $row['id'];?></td>
		<td width="30%"<?php echo $styl; ?>><?php echo $comp_name;?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $loc_name;?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $dated;?></td>
		<td width="5%"<?php echo $styl; ?>><?php echo $trans_type;?></td>
		<td width="10%" style="text-align:right;<?php echo $styl2; ?>" ><?php echo $amount;?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
    </tr>
	</a>
	<?php }?>
                
                </tbody>
              </table>
            </div>

<?php
}		
//Pending End
// Petty Cash					

?>

<?php

// Petty Cash 
//ALl Pending
if(isset($_POST['sub48'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>

		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">

<?php
						
							$doc_type	= 'PC';
							$pcnt_pc = 0;
					//echo $role. "<BR>";
							
							$id_var1 = '0';
							$id_var2 = '0';
					//echo $role. "<BR>";
								if ( $role == 'Checker - Account' || $role =='Accountant' || $role == 'Checker' || $role == 'Project Manager' || $role == 'HOD - Account' ){
									$id_var1 = '';
									$sql = " SELECT max(doc_id) as doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' ";
									//echo $sql. "<BR>";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var1 .= $rs['doc_id'].',';
									}
									$id_var1 .= '0';
									
									/* $id_var2 = '';
									$sql = " SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' ";
									//echo $sql. "<BR>";
									$qry = mysqli_query($con,$sql);
									while ( $rs = mysqli_fetch_array($qry)){
										$id_var2 .= $rs['doc_id'].',';
									} */
									$id_var2 .= '0';
									$sql = " SELECT * from sma_pettycash where approval_status in ( 'Pending', 'Verified', 'Prepared', 'Submited' ) and ( id in ( $id_var1 ) or id in ( $id_var2 ) ) and del!='Y' ";
									
								}
								
								if( $user=='Admin' ){
									$sql = " SELECT * from sma_pettycash where approval_status in ( 'Pending', 'Verified', 'Prepared', 'Submited' )  and del!='Y' ";
								}	
								
						$sql .= ' order by id desc ';		
						//echo $sql."<BR>";	
						
		$modulePath1 = 'petty/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
					<th>SrNo.</th>
					<th>Company</th>
					<th>Location</th>
					<th>Date</th>
					<th>Type</th>
					<th>Total Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$comp_name 	= $r1['comp_name'];
		
		$location_id  = $row['location_id'];
		$sql  = "SELECT * from sma_location where id = '$location_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$loc_name 	= $r1['loc_name'];
		
		$trans_type = $row['trans_type'];	
		$pc_id 		= $row["id"];
		$amount = 0;
		$sql  = "SELECT sum(amount) as amount, trans_type FROM `sma_pettycash_exp` where  approval_ref_no = '$pc_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r1 = mysqli_fetch_array($res1)){
			if($trans_type =='R'){
				$amount		-= $r1['amount'];
				$changed_by = $row['draft_by'];
			}
			else if($trans_type =='P'){
				$amount		+= $r1['amount'];
				$changed_by = $row['changed_by'];
			}
		}
		
		
		if($amount<0){
			$amount = $amount * -1;
		}	
			 
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		if( $dated=='01-01-1970' ){ $dated='';}
		
			$styl  = '';
			$styl2 = '';
			$del   = $row['del'];
			if($del =='Y'){
						
				$styl = "style='bgcolor:powderblue;color:red;' ";
				$styl2 = "bgcolor:powderblue;color:red; ";
							
			}
		
			$status = $row['status'];
			if($searchfu=='U'){
				$status = "UnPaid";
			}
			
		$baseurl1 	= $baseurl.$modulePath1.'pettycash_expense.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "pettycash_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"<?php echo $styl; ?>><?php echo $row['id'];?></td>
		<td width="30%"<?php echo $styl; ?>><?php echo $comp_name;?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $loc_name;?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $dated;?></td>
		<td width="5%"<?php echo $styl; ?>><?php echo $trans_type;?></td>
		<td width="10%" style="text-align:right;<?php echo $styl2; ?>" ><?php echo $amount;?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
    </tr>
	</a>
	<?php }?>
                
                </tbody>
              </table>
            </div>

<?php
}	
//ALl Pending	
// Petty Cash					

?>



<?php

// Petty Cash 
//Approved
if(isset($_POST['sub49'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>

		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">

<?php
						
							$doc_type	= 'PC';
							$pcnt_pc = 0;
					//echo $role. "<BR>";
								
							$id_var1 = '0';
							$id_var2 = '0';
							$var_null ='';
					//echo $role. "<BR>";
								if ( $role == 'Checker - Account' || $role =='Accountant' || $role == 'Checker' || $role == 'Project Manager' || $role == 'HOD - Account' ){
									$id_var1 = '';
									$sql = " SELECT max(doc_id) as doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' ";
									//echo $sql. "<BR>";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var1 .= $rs['doc_id'].',';
										$var_null .= $rs['doc_id'];
									}
									if(empty($var_null)){
										$id_var1 = '0';
									}
									else {
										$id_var1 .= '0';
									}
									
									 $id_var2 = '';
									$sql = " SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' ";
									//echo $sql. "<BR>";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var2 .= $rs['doc_id'].',';
										$var_null .= $rs['doc_id'];
									}
									if(empty($var_null)){
										$id_var2 = '0';
									}
									else {
										$id_var2 .= '0';
									}
									
									$sql = " SELECT * from sma_pettycash where approval_status in ( 'Approved' ) and ( id in ( $id_var1 ) or id in ( $id_var2 ) || (draft_by = '$user') ) and del!='Y' ";
									
								}
								else {
									$sql = " SELECT * from sma_pettycash where draft_by = '$user' and approval_status in ( 'Approved' )  and del!='Y' ";
								}	
								
								
								if( $user=='Admin'  ){
									$sql = " SELECT * from sma_pettycash where approval_status in ( 'Approved' )  and del!='Y' ";
								}	
								
						$sql .= ' order by id desc ';		
						//echo $sql."<BR>";	
						
		$modulePath1 = 'petty/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
					<th>SrNo.</th>
					<th>Company</th>
					<th>Location</th>
					<th>Date</th>
					<th>Type</th>
					<th>Total Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$comp_name 	= $r1['comp_name'];
		
		$location_id  = $row['location_id'];
		$sql  = "SELECT * from sma_location where id = '$location_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$loc_name 	= $r1['loc_name'];
		
		$trans_type = $row['trans_type'];	
		$pc_id 		= $row["id"];
		$amount = 0;
		$sql  = "SELECT sum(amount) as amount, trans_type FROM `sma_pettycash_exp` where  approval_ref_no = '$pc_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r1 = mysqli_fetch_array($res1)){
			if($trans_type =='R'){
				$amount		-= $r1['amount'];
				$changed_by = $row['draft_by'];
			}
			else if($trans_type =='P'){
				$amount		+= $r1['amount'];
				$changed_by = $row['changed_by'];
			}
		}
		
		
		if($amount<0){
			$amount = $amount * -1;
		}	
			 
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		if( $dated=='01-01-1970' ){ $dated='';}
		
			$styl  = '';
			$styl2 = '';
			$del   = $row['del'];
			if($del =='Y'){
						
				$styl = "style='bgcolor:powderblue;color:red;' ";
				$styl2 = "bgcolor:powderblue;color:red; ";
							
			}
		
			$status = $row['status'];
			if($searchfu=='U'){
				$status = "UnPaid";
			}
			
		$baseurl1 	= $baseurl.$modulePath1.'pettycash_expense.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "pettycash_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"<?php echo $styl; ?>><?php echo $row['id'];?></td>
		<td width="30%"<?php echo $styl; ?>><?php echo $comp_name;?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $loc_name;?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $dated;?></td>
		<td width="5%"<?php echo $styl; ?>><?php echo $trans_type;?></td>
		<td width="10%" style="text-align:right;<?php echo $styl2; ?>" ><?php echo $amount;?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
    </tr>
	</a>
	<?php }?>
                
                </tbody>
              </table>
            </div>

<?php
}	
//Approved	
// Petty Cash					

?>



<?php

// Petty Cash 
//Approved
if(isset($_POST['sub50'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>

		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">

<?php
						
							$doc_type	= 'PC';
								
							$rcnt_pc = 0;
							$id_var1 = '0';
							$id_var2 = '0';
							$var_null ='';
					//echo $role. "<BR>";
								if ( $role == 'Checker - Account' || $role =='Accountant' || $role == 'Checker' || $role == 'Project Manager' || $role == 'HOD - Account' ){
									$id_var1 = '';
									$sql = " SELECT max(doc_id) as doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' ";
									//echo $sql. "<BR>";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var1 .= $rs['doc_id'].',';
										$var_null .= $rs['doc_id'];
									}
									if(empty($var_null)){
										$id_var1 = '0';
									}
									else {
										$id_var1 .= '0';
									}
									
									 $id_var2 = '';
									$sql = " SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' ";
									//echo $sql. "<BR>";
									$qry = mysqli_query($con,$sql);
									while($rs = mysqli_fetch_array($qry)){
										$id_var2 .= $rs['doc_id'].',';
										$var_null .= $rs['doc_id'];
									}
									if(empty($var_null)){
										$id_var2 = '0';
									}
									else {
										$id_var2 .= '0';
									}
									
									$sql = " SELECT * from sma_pettycash where approval_status in ( 'Rejected' ) and ( id in ( $id_var1 ) or id in ( $id_var2 ) || (draft_by = '$user') ) and del!='Y' ";
									
								}
								else {
									$sql = " SELECT * from sma_pettycash where draft_by = '$user' and approval_status in ( 'Rejected' )  and del!='Y' ";
								}	
								
								
								if( $user=='Admin' ){
									$sql = " SELECT * from sma_pettycash where approval_status in ( 'Rejected' )  and del!='Y' ";
								}	
								
						$sql .= ' order by id desc ';		
						//echo $sql."<BR>";	
						
		$modulePath1 = 'petty/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
					<th>SrNo.</th>
					<th>Company</th>
					<th>Location</th>
					<th>Date</th>
					<th>Type</th>
					<th>Total Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$comp_name 	= $r1['comp_name'];
		
		$location_id  = $row['location_id'];
		$sql  = "SELECT * from sma_location where id = '$location_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$loc_name 	= $r1['loc_name'];
		
		$trans_type = $row['trans_type'];	
		$pc_id 		= $row["id"];
		$amount = 0;
		$sql  = "SELECT sum(amount) as amount, trans_type FROM `sma_pettycash_exp` where  approval_ref_no = '$pc_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r1 = mysqli_fetch_array($res1)){
			if($trans_type =='R'){
				$amount		-= $r1['amount'];
				$changed_by = $row['draft_by'];
			}
			else if($trans_type =='P'){
				$amount		+= $r1['amount'];
				$changed_by = $row['changed_by'];
			}
		}
		
		
		if($amount<0){
			$amount = $amount * -1;
		}	
			 
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		if( $dated=='01-01-1970' ){ $dated='';}
		
			$styl  = '';
			$styl2 = '';
			$del   = $row['del'];
			if($del =='Y'){
						
				$styl = "style='bgcolor:powderblue;color:red;' ";
				$styl2 = "bgcolor:powderblue;color:red; ";
							
			}
		
			$status = $row['status'];
			if($searchfu=='U'){
				$status = "UnPaid";
			}
			
		$baseurl1 	= $baseurl.$modulePath1.'pettycash_expense.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "pettycash_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"<?php echo $styl; ?>><?php echo $row['id'];?></td>
		<td width="30%"<?php echo $styl; ?>><?php echo $comp_name;?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $loc_name;?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $dated;?></td>
		<td width="5%"<?php echo $styl; ?>><?php echo $trans_type;?></td>
		<td width="10%" style="text-align:right;<?php echo $styl2; ?>" ><?php echo $amount;?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
    </tr>
	</a>
	<?php }?>
                
                </tbody>
              </table>
            </div>

<?php
}
//Approved	
// Petty Cash					

?>

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(function () {
        $("#prtable").DataTable();
    });
</script>

