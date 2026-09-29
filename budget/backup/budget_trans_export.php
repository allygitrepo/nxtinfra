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
					<th  style='text-align:left;'>Dated</th>
					<th>Party/Items/Expense</th>
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
		
		$product_name 		= $row['items'];
		
		$budget_id 			= $row['budget_id'];
		$check_var 			= $row['check_var'];
		
		
		if($budget_id_prev	!= $budget_id){
			$sql = " SELECT * from sma_budget WHERE id = '$budget_id' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$total_budget 		= $r2['total_budget'];
			$running_balance_budget = $total_budget;
		
			$message .= "<tr>
				<td width='10%' style='text-align:left;'></td>
				<td width='10%' style='text-align:left;'></td>
				<td width='10%' style='text-align:left;'></td>
				<td width='15%' style='text-align:left;'><b>Opening Balance</b></td>
				<td width='10%' style='text-align:left;'></td>
				<td width='10%' style='text-align:left;'></td>
				<td width='10%' style='text-align:left;'></td>
				<td width='10%' style='text-align:right;'>". moneyFormatIndia($total_budget)."</td>
			</tr> ";

		}
		
		$budget_id_prev		= $budget_id;
		
		$dated 				= date('d-m-Y', strtotime($row['doc_date']));
		$blocked_budget 	= $row['blocked_budget'];
		$used_budget 		= $row['used_budget'];
		$adjustment_budget 	= $row['adjustment_budget'];
		//$balance_budget 	= $row['balance_budget'];
		if($check_var=='C' && $doc_type=='PO' ){
			
		}
		
		
		if( $doc_type=='AP' ){
			
			$doc_type = 'Approval Memo';
		
			$running_balance_budget		= $running_balance_budget - $adjustment_budget - ( $blocked_budget ); //+ $used_budget
		
		}
		else if( $doc_type=='PO' ){
			
			$doc_type = 'Purchase Order';
			
			if($check_var=='C'){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $blocked_budget ); //+ $used_budget
			}
			
		}
		else if( $doc_type=='SI' ){
			$doc_type = 'Supplier Invoice';
		}
		else if( $doc_type=='OP' ){
			
			$doc_type = 'Operating Expense';
			
			if(empty($check_var)){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $used_budget  ); //+ $blocked_budget
			}
			
		}
		else if( $doc_type=='TE' ){
			
			$doc_type = 'Travel Expense';
			
			if(empty($check_var)){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $used_budget  ); //+ $blocked_budget
			}
			
		}
		else if( $doc_type=='RE' ){
			
			$doc_type = 'Regular Expense';
			
			if(empty($check_var)){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $used_budget  ); //+ $blocked_budget
			}
			
		}
		
		
		$baseurl1 = $baseurl.$modulePath1.'budget_trans_list.php?sub=edit&id='.$row["id"];
					
		$message .= '<tr>
			<td width="10%" style="text-align:left;">'. $doc_type.'</td>
			<td width="10%" style="text-align:left;">'. $doc_no.'</td>
			<td width="10%" style="text-align:left;">'. $dated.'</td>
			<td width="15%" style="text-align:left;">'. $product_name.'</td>
			<td width="10%" style="text-align:right;">'. moneyFormatIndia($blocked_budget).'</td>
			<td width="10%" style="text-align:right;">'. moneyFormatIndia($used_budget).'</td>
			<td width="10%" style="text-align:right;">'. moneyFormatIndia($adjustment_budget).'</td>
			<td width="10%" style="text-align:right;">'. moneyFormatIndia($running_balance_budget).'</td>
			</tr>';

			
	}
	
//echo $message;
//exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    
	if($prn=='excel'){
		$fl_name = 'budget_trans_export.xls';
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
