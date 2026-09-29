<?php

include("../dbcon.php");
include("../baseurl.php");
$modulePath = "budget_proposal/";

/* SELECT d.comp_id, d.comp_code, a.account_year, a.total_budget, a.adjustment_budget, b.id as 'budget_group', b.name as 'budget_name', c.id as 'budget_subgroup', c.budget_head, c.budget_code FROM `sma_budget` a , sma_budget_name b, sma_budget_subgroup c, company d where a.project = d.comp_id and a.budget_name = b.id and a.budget_head = c.id and account_year = '2023-2024';
 */

$hdr_id = $_GET['hdr_id'];
$sql = "SELECT * from sma_budget_proposal WHERE id =  '$hdr_id' ";
//echo $sql ."<BR>";
$q2 	= mysqli_query($con, $sql);
echo mysqli_error($con);	
$r2 	= mysqli_fetch_array($q2);
$company_id 	= $r2['company_id'];
$account_year 	= $r2['account_year'];
$version_no 	= $r2['current_version'];

$sql = " SELECT from_date, to_date, short_fy_code, finyear_prefix, status FROM `sma_financial_year` where status = 'Y' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$from_date 	= $r2['from_date'];
		$short_fy_code 	= $r2['short_fy_code'];
	
//$account_year = '2023-2024';
$sql 	= "select * from company where 1 and comp_id = '$company_id' order by comp_id ";
//echo $sql ."<BR>";
	$q2 	= mysqli_query($con, $sql);
	echo mysqli_error($con);	
while(	$r2 	= mysqli_fetch_array($q2)){
	
	$company_name 	= $r2['comp_name'];
	$comp_code		= $r2['comp_code'];
	$comp_id		= $r2['comp_id'];
		
	/* $sql = "INSERT INTO sma_budget_proposal (company_id, account_year, status) VALUES('$comp_id', '$account_year','Draft') ";
	mysqli_query($con, $sql);
	$hdr_id = mysqli_insert_id($con);
	 */
	$sql = " SELECT d.comp_id, d.comp_code, a.account_year, a.total_budget, a.adjustment_budget, a.used_budget, b.id as 'budget_group', 
				b.name as 'budget_name', c.id as 'budget_subgroup', c.budget_head, c.budget_code , a.board_approved_budget
					FROM `sma_budget` a , sma_budget_name b, sma_budget_subgroup c, company d 
						WHERE a.project = '$comp_id' and a.project = d.comp_id and a.budget_name = b.id and a.budget_head = c.id 
								and account_year = '$short_fy_code' ";
//echo $sql ."<BR>";
								
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
		$account_year			= $row['account_year'];
		$board_approved_budget	= $row['board_approved_budget'];
		
		$sql = " select * from sma_budget_proposal_details where hdr_id = '$hdr_id' and version_no = '$version_no' and cost_group_id= '$budget_group' and cost_subgroup_id = '$budget_subgroup' ";
//echo $sql ."<BR>";
		mysqli_query($con, $sql);
		echo mysqli_error($con);	
		$rowaffect = mysqli_affected_rows($con);
		
		if($rowaffect==0){
			$sql = " INSERT INTO sma_budget_proposal_details (hdr_id, cost_group_id, cost_subgroup_id, last_year_open_bal, last_year_comsume, current_year_op_bal, current_year_comsume, version_no, board_approved_budget ) 
						VALUES('$hdr_id', '$budget_group','$budget_subgroup', '$last_year_open_bal', '$used_budget', '$last_year_open_bal', '$used_budget', '$version_no', '$board_approved_budget' ) ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		}
	/* 	else {
			$sql = " UPDATE sma_budget_proposal_details set current_year_op_bal = '$last_year_open_bal', current_year_comsume = '$used_budget' where hdr_id = '$hdr_id' and version_no = '$version_no' and cost_group_id= '$budget_group' and cost_subgroup_id = '$budget_subgroup' ";
echo $sql ."<BR>";
 			mysqli_query($con, $sql);
			echo mysqli_error($con);	 
				
		} */	
//echo $sql ."<BR>";

//exit('Exit Here...');
	
	}

}

	$modulePath1 = "budget_proposal/";
	$baseurl1 = $baseurl.$modulePath1.'budget_proposal.php?sub=edit&hdr_id='.$hdr_id;
	echo "<script>window.location.href='$baseurl1';</script>";			
	exit();

?>


