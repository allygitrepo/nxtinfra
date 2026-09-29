<?php
	include("../dbcon.php");

	$prn='excel';
		
	$hdr_id = $_GET['hdr_id'];
	
 	$sql = " SELECT from_date, to_date, short_fy_code, finyear_prefix, status FROM `sma_financial_year` where status = 'Y' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$from_date 	= $r2['from_date'];
		$short_fy_code 	= $r2['short_fy_code'];
		
		$last_year		= date('Y', strtotime($from_date)) - 1;
		$last_year		.= '-'. (date('y', strtotime($from_date)) - 0);
		
		$current_year	= date('Y', strtotime($from_date))-0;
		$current_year	.= '-'.(date('y', strtotime($from_date)) + 1);
		
		$next_year		= date('Y', strtotime($from_date)) + 1;
		$next_year		.= '-'.(date('y', strtotime($from_date)) + 2);
		
	$message = '';
	$message .= "<table border='0' cellspacing='0' style='width: 100%; text-align: center; font-size: 12px;'>
			<tr><td style='width: 80%;;'>Budget Proposal </td><td> Date:" . date('d-m-Y') ."</td></tr></table>";	
	
	$sql="SELECT * FROM `sma_budget_proposal` where 1 and id = '$hdr_id' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$row = mysqli_fetch_array($result);
	
		$dated 				= $row['draft_date'];
		$company_id			= $row['company_id'];
		$account_year		= $row['account_year'];
		$current_version	= $row['current_version'];
		
		$status 			= $row['status'];
	
		$sql 	= "select * from company where 1 and comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$company_name 	= $r2['comp_name'];
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; font-size: 12px;'>";	
	
	$message .= "<tr><td>Date : ". date('d-m-Y', strtotime($dated)) ."</td><td> Company : " .	$company_name . 
	"</td><td>Account Year : " . $account_year . 
	"</td><td>Version : ". $current_version. ' </td>' ;
//			<th width='10%' style='text-align:right;'>Last Year Open Budget</th>
//			<th width='10%' style='text-align:right;'>Last Year Actual</th>
		
	$message .= "<tr><td width='10%' style='text-align:right;'>#</td>
			<th width='20%'>Cost Group </th>
			<th width='20%'>Cost Sub Group </th>
			
			<th width='10%' style='text-align:right;'>Board Approved Budget</th>
			<th width='10%' style='text-align:right;'>$current_year Total</th>
			<th width='10%' style='text-align:right;'>Upto 31-12-23  Actual</th>
			 
			<th width='10%' style='text-align:right;'> $current_year Actual Tally</th>
			<th width='10%' style='text-align:right;'> $current_year Balance</th>
			<th width='10%' style='text-align:right;'> $next_year Provisional</th>
			<th width='10%' style='text-align:right;'>Proposed Total Budget</th>
			
			<th width='10%' style='text-align:right;'>Remark</th>
			
			<th width='10%' style='text-align:right;'>April</th>
			<th width='10%' style='text-align:right;'>May</th>
			<th width='10%' style='text-align:right;'>June</th>
			<th width='10%' style='text-align:right;'>July</th>
			<th width='10%' style='text-align:right;'>August</th>
			<th width='10%' style='text-align:right;'>September</th>
			<th width='10%' style='text-align:right;'>October</th>
			<th width='10%' style='text-align:right;'>November</th>
			<th width='10%' style='text-align:right;'>December</th>
			<th width='10%' style='text-align:right;'>January</th>
			<th width='10%' style='text-align:right;'>February</th>
			<th width='10%' style='text-align:right;'>March</th>
		</tr>";
	
	$sql="SELECT * from sma_budget_proposal_details where 1 and hdr_id = '$hdr_id' ";
	
	$sql .= " order by id ";
//echo $sql."<BR>";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
	
		$cost_group_id		 		= $row['cost_group_id'];
		$cost_subgroup_id 			= $row['cost_subgroup_id'];
		
		$last_year_open_bal 		= $row['last_year_open_bal'];
		$last_year_comsume			= $row['last_year_comsume'];
		$current_year_op_bal 		= $row['current_year_op_bal'];
		
		$current_year_open_bal 		= $row['current_year_open_bal'];
		$current_year_comsume		= $row['current_year_comsume'];
		
		$current_year_provision		= $row['current_year_provision'];
		$next_year_budget			= $row['next_year_budget'];
		
		$board_approved_budget		= $row['board_approved_budget'];
		$actual_tally				= $row['actual_tally'];
		$remarks					= $row['remarks'];
		
		
		$monthly_budget				= round($next_year_budget / 12,2);
		
		if($monthly_budget==0){
			$monthly_budget='';
		}	
		$sql 	= "SELECT * FROM sma_budget_name where 1 and id = '$cost_group_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$cost_group				= $r2['name'];
		
		$sql 	= "SELECT * FROM sma_budget_subgroup WHERE 1 AND id = '$cost_subgroup_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$cost_subgroup				= $r2['budget_head'];
		$budget_code				= $r2['budget_code'];
		
		$rid						= $row['id'];

		$last_year_open_bal_v 		= moneyFormatIndiaa($last_year_open_bal);
		$last_year_comsume_v 		= moneyFormatIndiaa($last_year_comsume);
		
		$current_year_open_bal_v 	= moneyFormatIndiaa($current_year_open_bal);
		$current_year_comsume_v 	= moneyFormatIndiaa($current_year_comsume);
		
		$current_year_balance_v		= ($current_year_op_bal - $current_year_comsume);
		
		$message .= "<tr>
					<td width='10%'  style='text-align:right;'>". ++$ii."</td>
					<td width='30%' >". $cost_group."</td>
					<td width='30%' >". $cost_subgroup."</td>
					
					<td width='10%' style='text-align:right;' >". $board_approved_budget."</td>
					<td width='10%' style='text-align:right;' >". $current_year_open_bal_v."</td>
					<td width='10%' style='text-align:right;' >". $current_year_comsume_v."</td>
					<td width='10%' style='text-align:right;' >". $actual_tally."</td>
					
					<td width='10%' style='text-align:right;' >". $current_year_balance_v."</td>
					<td width='10%' style='text-align:right;' >". $current_year_provision."</td>
					<td width='10%'style='text-align:right;' >". $next_year_budget."</td>
					<td width='10%'style='text-align:right;' >". $remarks."</td>
					
					<td width='10%'style='text-align:right;' >". $monthly_budget."</td>
					<td width='10%'style='text-align:right;' >". $monthly_budget."</td>
					<td width='10%'style='text-align:right;' >". $monthly_budget."</td>
					<td width='10%'style='text-align:right;' >". $monthly_budget."</td>
					<td width='10%'style='text-align:right;' >". $monthly_budget."</td>
					<td width='10%'style='text-align:right;' >". $monthly_budget."</td>
					<td width='10%'style='text-align:right;' >". $monthly_budget."</td>
					<td width='10%'style='text-align:right;' >". $monthly_budget."</td>
					<td width='10%'style='text-align:right;' >". $monthly_budget."</td>
					<td width='10%'style='text-align:right;' >". $monthly_budget."</td>
					<td width='10%'style='text-align:right;' >". $monthly_budget."</td>
					<td width='10%'style='text-align:right;' >". $monthly_budget."</td>
				</tr>";

	}
	
		$message .= "</table>";
	
//echo $message."<BR>";
//exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	
	$fl_name = 'budget_proposal_'.date("d-m-Y").'.xls';
	
		header("Content-type: application/xls");
		Header("Content-Disposition: attachment; filename=$fl_name");
		print $message;

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
<!-- Ruchi ended-->	
