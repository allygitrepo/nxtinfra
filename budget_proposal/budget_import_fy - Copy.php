<?php

include("../dbcon.php");
$modulePath = "budget_proposal/";

/* SELECT d.comp_id, d.comp_code, a.account_year, a.total_budget, a.adjustment_budget, b.id as 'budget_group', b.name as 'budget_name', c.id as 'budget_subgroup', c.budget_head, c.budget_code FROM `sma_budget` a , sma_budget_name b, sma_budget_subgroup c, company d where a.project = d.comp_id and a.budget_name = b.id and a.budget_head = c.id and account_year = '2023-2024';
 */

$account_year = '2023-2024';
$sql 	= "select * from company where 1 order by comp_id ";
	$q2 	= mysqli_query($con, $sql);
while(	$r2 	= mysqli_fetch_array($q2)){
	
	$company_name 	= $r2['comp_name'];
	$comp_code		= $r2['comp_code'];
	$comp_id		= $r2['comp_id'];
		
	$sql = "INSERT INTO sma_budget_proposal (company_id, account_year, status) VALUES('$comp_id', '$account_year','Draft') ";
	mysqli_query($con, $sql);
	$hdr_id = mysqli_insert_id($con);
	
	$sql = " SELECT d.comp_id, d.comp_code, a.account_year, a.total_budget, a.adjustment_budget, a.used_budget, b.id as 'budget_group', 
				b.name as 'budget_name', c.id as 'budget_subgroup', c.budget_head, c.budget_code 
					FROM `sma_budget` a , sma_budget_name b, sma_budget_subgroup c, company d 
						WHERE a.project = '$comp_id' and a.project = d.comp_id and a.budget_name = b.id and a.budget_head = c.id 
								and account_year = '$account_year' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);	
	while($row = mysqli_fetch_array($result)){
		
		$company_id 			= $row['comp_id'];
		$comp_code 				= $row['comp_code'];
		$total_budget 			= $row['total_budget'];
		$adjustment_budget 		= $row['adjustment_budget'];
		$last_year_open_bal		= $total_budget + $adjustment_budget;
		$budget_group 			= $row['budget_group'];
		$budget_subgroup 		= $row['budget_subgroup'];
		$used_budget 			= $row['used_budget'];
		
		$sql = "INSERT INTO sma_budget_proposal_details (hdr_id, cost_group_id, cost_subgroup_id, last_year_open_bal, last_year_comsume) 
					VALUES('$hdr_id', '$budget_group','$budget_subgroup', '$last_year_open_bal', '$used_budget' ) ";
		mysqli_query($con, $sql);
//exit('Exit Here...');
	
	}

}

?>

