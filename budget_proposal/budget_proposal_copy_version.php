<?php

	session_start(); 	

include("../dbcon.php");
include("../baseurl.php");
$modulePath = "budget_proposal/";

$user   = $_SESSION['user'];

$hdr_id 	= $_GET['hdr_id'];
$old_hdr_id = $hdr_id;
$sql = "SELECT * from sma_budget_proposal WHERE id =  '$hdr_id' ";
//echo $sql ."<BR>";
$q2 	= mysqli_query($con, $sql);
echo mysqli_error($con);	
$r2 	= mysqli_fetch_array($q2);
$company_id 	= $r2['company_id'];
$account_year 	= $r2['account_year'];
$current_version	= $r2['current_version'];
$next_version_no 	= $r2['current_version'] + 1;
	
//$account_year = '2023-2024';
$sql 	= "select * from company where 1 and comp_id = '$company_id' order by comp_id "; //
//echo $sql ."<BR>";
	$q2 	= mysqli_query($con, $sql);
	echo mysqli_error($con);	
while(	$r2 	= mysqli_fetch_array($q2)){
	
	$company_name 	= $r2['comp_name'];
	$comp_code		= $r2['comp_code'];
	$comp_id		= $r2['comp_id'];
		
	$sql = "INSERT INTO sma_budget_proposal (company_id, account_year, current_version, status, draft_by, draft_date) VALUES('$comp_id', '$account_year', '$next_version_no', 'Draft', '$user', now() ) ";
	mysqli_query($con, $sql);
	$new_hdr_id = mysqli_insert_id($con);
	 
	$sql = " SELECT d.comp_id, d.comp_code, a.account_year, a.total_budget, a.adjustment_budget, a.used_budget, b.id as 'budget_group', 
				b.name as 'budget_name', c.id as 'budget_subgroup', c.budget_head, c.budget_code 
					FROM `sma_budget` a , sma_budget_name b, sma_budget_subgroup c, company d 
						WHERE a.project = '$comp_id' and a.project = d.comp_id and a.budget_name = b.id and a.budget_head = c.id 
								and account_year = '$account_year' ";
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
		
		$sql = " select * from sma_budget_proposal_details where hdr_id = '$old_hdr_id' and version_no = '$current_version' and cost_group_id= '$budget_group' and cost_subgroup_id = '$budget_subgroup' ";
//echo $sql ."<BR>";
		$q2 	= mysqli_query($con, $sql);
			echo mysqli_error($con);	
		$rowaffect = mysqli_affected_rows($con);
		$r2 	= mysqli_fetch_array($q2);
		$last_year_open_bal 	= $r2['last_year_open_bal'];
		$last_year_comsume 		= $r2['last_year_comsume'];
		$current_year_op_bal 	= $r2['current_year_op_bal'];
		$current_year_comsume 	= $r2['current_year_comsume'];
		$current_year_provision	= $r2['current_year_provision'];
		$next_year_budget 		= $r2['next_year_budget'];
		$actual_tally			= $r2['actual_tally'];
		$board_approved_budget			= $r2['board_approved_budget'];
		
	//	if($rowaffect==0){
			$sql = "INSERT INTO sma_budget_proposal_details (hdr_id, cost_group_id, cost_subgroup_id, last_year_open_bal, last_year_comsume, current_year_op_bal, current_year_comsume, current_year_provision, next_year_budget, version_no, actual_tally, board_approved_budget) 
			VALUES('$new_hdr_id', '$budget_group','$budget_subgroup', '$last_year_open_bal', '$last_year_comsume', '$current_year_op_bal', '$current_year_comsume', '$current_year_provision','$next_year_budget', '$next_version_no', '$actual_tally', '$board_approved_budget' ) ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
	//	}
//echo $sql ."<BR>";

//exit('Exit Here...');
	
	}

}

	$sql = "UPDATE sma_budget_proposal set status = 'Closed' WHERE id =  '$old_hdr_id' ";
	mysqli_query($con, $sql);

	$modulePath1 = "budget_proposal/";
	$baseurl1 = $baseurl.$modulePath1.'budget_proposal.php?sub=edit&hdr_id='.$new_hdr_id;
	echo "<script>window.location.href='$baseurl1';</script>";			
	exit();

?>


