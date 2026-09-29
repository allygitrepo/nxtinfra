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
//	$department = $_POST['department'];	
//	$supplier_id= $_POST['supplier_id'];	
//	$company_id= $_POST['company_id'];
		
	$message ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='8'> Used Budget Transaction </th></tr></table>";
	
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr>
					<th>Doc Type.</th>
					<th>Doc.No.</th>
					<th>Against AP/PO SrNo.</th>
					<th  style='text-align:left;'>Dated</th>
					<th>Party</th>
					<th>Items/Expense</th>
					<th  style='text-align:right;'>PO Amount</th>
					<th  style='text-align:right;'>Blocked</th>
					<th  style='text-align:right;'>Consumed</th>
					<th  style='text-align:right;'>Adjustment</th>
					<th  style='text-align:right;'>Balance</th>
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 12pt;'>";
				
		$grand_total_amount = '';
		$budget_head_prev	= '';
		$project_prev = '';
	
	$sql = $_SESSION['sql'];
	
//echo $sql."<BR>";
//exit();

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$doc_type	 		= $row['doc_type'];
		$doc_no	 			= $row['doc_no'];
		$po_srno			= $row['po_srno'];
		
		$product_name 		= $row['items'];
		$status		 		= $row['status'];
		
		$budget_id 			= $row['budget_id'];
		$check_var 			= $row['check_var'];
		$party_id 			= $row['party_id'];
		$doctype 			= $row['doctype'];
		
		$party_name = '';
		if($doc_type =='AP' || $doc_type =='PO' || $doc_type =='SI'  || $doc_type =='CE' || $doc_type =='OP' ){
			$sql = " SELECT * from sma_party_mst WHERE id = '$party_id' ";
				
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$party_name 		= $r2['party_name'];
		}
		else {
			$sql = " SELECT * from sma_user WHERE id = '$party_id' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$party_name 		= $r2['username'];
		}
		
		if($budget_id_prev	!= $budget_id){
			$sql = " SELECT * from sma_budget WHERE id = '$budget_id' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$total_budget 			= $r2['total_budget'];
			$budget_name 		= $r2['budget_name'];
			$budget_head 		= $r2['budget_head'];
			$budget_code 		= $r2['budget_code'];
			//$adjustment_budget 	= $r2['adjustment_budget'];
			$running_balance_budget = $total_budget ;
			
			
			$sql = " SELECT * from sma_budget_name WHERE id = '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 		= $r2['name'];
			//$running_balance_budget = $total_budget + $adjustment_budget;
		
			$sql = " SELECT * from sma_budget_subgroup WHERE id = '$budget_head' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_head 		= $r2['budget_head'];
			
			$message .= '<tr>
				<td width="10%" style="text-align:left;"></td>
				<td width="10%" style="text-align:left;"></td>
				<td width="10%" style="text-align:left;"></td>
				<td width="10%" style="text-align:left;"></td>
				<td width="15%" style="text-align:left;"><b>Opening Balance</b></td>
				<td width="10%" style="text-align:left;">'. $budget_name .'</td>
				<td width="10%" style="text-align:left;">'. $budget_head .'</td>
				<td width="10%" style="text-align:left;">'. $budget_code .'</td>
				<td width="10%" style="text-align:right;"></td>
				<td width="10%" style="text-align:right;">'. moneyFormatIndia($total_budget).'</td>
		  
				</td>
			</tr>';
	
		}
		
		$budget_id_prev		= $budget_id;
		
		$dated 				= date('d-m-Y', strtotime($row['doc_date']));
		$blocked_budget 	= $row['blocked_budget'];
		$used_budget 		= $row['used_budget'];
		$adjustment_budget 	= $row['adjustment_budget'];
		//$balance_budget 	= $row['balance_budget'];
		
		$po_amount = '';
		//$blocked_budget	='';
		if( $doc_type=='AP' ){
			
			$doc_type = 'Approval Memo';
			if($doctype=='AP-ADJ'){
				if($blocked_budget==0){
					continue;	
				}	
				$doc_type = 'Approval Memo Reversal';
				$adjustment_budget	= $blocked_budget * -1;
				$blocked_budget ='';
			}	
			else {
				$running_balance_budget		= $running_balance_budget - $adjustment_budget - ( $blocked_budget ); //+ $used_budget
			}
			
		}
		else if( $doc_type=='PO' ){
			
			//$doc_type = 'Purchase Order';
			
			if($check_var=='C'){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $blocked_budget ); //+ $used_budget
				$po_amount = '';
				$doc_type = 'Approval Cum PO';
			
			}
			else {
				
				if($blocked_budget<0){
					$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $blocked_budget ); 
					$po_amount = $blocked_budget;
					$blocked_budget	='';
					$doc_type = 'PO Against Approval'. ' - '. $status ;
					
				}
				else{
					$po_amount = $blocked_budget;
					$blocked_budget	='';
					$doc_type = 'PO Against Approval';
				}
				
			
			}	
			
		}
		else if( $doc_type=='SI' ){
			$doc_type = 'Supplier Invoice';
		}
		else if( $doc_type=='OP' ){
			
			$doc_type = 'Operating Expense';
			
			if(empty($check_var)){
				$running_balance_budget	= $running_balance_budget  - ( $used_budget  ); //+ $blocked_budget
			}
			
		}
		else if( $doc_type=='TE' ){
			
			$doc_type = 'Travel Expense';
			
			if(empty($check_var)){
				$running_balance_budget	= $running_balance_budget  - ( $used_budget  ); //+ $blocked_budget
			}
			
		}
		else if( $doc_type=='RE' ){
			
			$doc_type = 'Regular Expense';
			
			if(empty($check_var)){
				$running_balance_budget	= $running_balance_budget  - ( $used_budget  ); //+ $blocked_budget
			}
			
		}
		else if( $doc_type=='BD' ){
			
			$doc_type = 'Budget Adjustment';
			
			if($check_var=='I'){
				$running_balance_budget	= $running_balance_budget + $adjustment_budget ; 
			}
			else if($check_var=='D'){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget ; 
				$adjustment_budget 		= $adjustment_budget * -1;
			}
			
		}
		
		
		$baseurl1 = $baseurl.$modulePath1.'budget_trans_list.php?sub=edit&id='.$row["id"];
		
		if($po_amount>0){
			$po_amount = moneyFormatIndia($po_amount);
		}
		else if ($po_amount<0){
			$po_amount = number_format($po_amount,2);
		}
		
		if($blocked_budget>0){
			$blocked_budget = moneyFormatIndia($blocked_budget);
		}
		else if ($blocked_budget<0){
			$blocked_budget = number_format($blocked_budget,2);
		}
	
		if($adjustment_budget==0){
			$adjustment_budget ='';
		}
		else {
			$adjustment_budget = number_format($adjustment_budget,2);
		}
		
		if($party_id> 0){
			$party_name = $party_name ;
		}
		
		if($running_balance_budget>0){
			$running_balance_budget_v = moneyFormatIndia($running_balance_budget);
		}
		else {
			$running_balance_budget_v = number_format($running_balance_budget,2);
			
		}
		
		if($used_budget>0){
			$used_budget_v = moneyFormatIndia($used_budget);
		}
		else {
			$used_budget_v = number_format($used_budget,2);
			
		}
		
		if($adjustment_budget>0){
			$adjustment_budget_v = moneyFormatIndia($adjustment_budget);
		}
		else {
			$adjustment_budget_v = number_format($adjustment_budget,2);
		}
		
	$message .= '<tr>
		<td width="15%" style="text-align:left;">'. $doc_type.'</td>
		<td width="10%" style="text-align:left;">'. $doc_no.'</td>
		<td width="10%" style="text-align:left;">'. $po_srno.'</td>
		<td width="10%" style="text-align:left;">'. $dated.'</td>
		<td width="15%" style="text-align:left;">'. $party_name.'</td>
		<td width="15%" style="text-align:left;">'. $product_name.'</td>
		<td width="10%" style="text-align:right;">'. $po_amount.'</td>
		<td width="10%" style="text-align:right;">'. $blocked_budget.'</td>
		<td width="10%" style="text-align:right;">'. ($used_budget_v).'</td>
		<td width="10%" style="text-align:right;">'. $adjustment_budget_v .'</td>
		<td width="10%" style="text-align:right;">'. ($running_balance_budget_v).'</td>
		</td>
    </tr>';
	
	 }

$message .= "</table> ";
	
	
//echo $message;
//exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    
	if($prn=='excel'){
		$fl_name = 'budget_trans_export_'.date('d-m-Y').'.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
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
