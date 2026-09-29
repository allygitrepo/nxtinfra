<?php

	session_start();
	
	include "../dbcon.php";
	include "../baseurl.php";

	$comid  = $_SESSION['comid'];

	$prn		= "excel";
	$from_date	= date('Y-m-d', strtotime($_POST['from_date']));
	$to_date	= date('Y-m-d', strtotime($_POST['to_date']));
	$department = $_POST['department'];	
	$supplier_id= $_POST['supplier_id'];	
	$company_id= $_POST['company_id'];	
			
	$sql = " TRUNCATE dashboard_budget_data ";
	mysqli_query($con,$sql);
	
	$sql 	= " SELECT * FROM sma_budget where 1 order by project, budget_name ";
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
	
		$project = $row['project'];
		$sql = "SELECT * from company where comp_id = '$project' ";
		$res = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($res);
		$comp_name 				= $r2['comp_name'];
		$comp_code 				= $r2['comp_code'];
		
		if(empty($comp_code)){
			continue;
		}	
		
		$budget_code 			= $row['budget_code'];		
		$budget_name			= $row['budget_name'];
		$cost_center_sub_group	= $row['budget_head'];
		
		//$account_year			= $r2['account_year'];
		
		$total_budget			= $row['total_budget'];
		/* $used_budget			= $row['used_budget'];
		$blocked_budget			= $row['blocked_budget'];
		$adjustment_budget		= $row['adjustment_budget'];
		$locked					= $row['locked']; */
		//$budget_status			= $row['budget_status'];
	
		$sql 		= "select * from sma_budget_name where id = '$budget_name' ";		
		$res 		= mysqli_query($con,$sql);
		$rw  		= mysqli_fetch_array($res);
		$cost_center_group		= $rw['name'];
			
		
		$april					= $row['april'];
		$may					= $row['may'];
		$june					= $row['june'];
		$july					= $row['july'];
		$august					= $row['august'];
		$september				= $row['september'];
		$october				= $row['october'];
		$november				= $row['november'];
		$december				= $row['december'];
		$january				= $row['january'];
		$february				= $row['february'];
		$march					= $row['march'];
		
		$yyyy_mm				= '2021-04';
		$sql = "SELECT comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group, sum(total_value) as used_budget 
			FROM `dashboard_trans_data` 
			WHERE comp_code = '$comp_code' and trim(budget_code) = '$budget_code' 
				and trim(cost_center_group) = '$cost_center_group' 
				and trim(cost_center_sub_group) = '$cost_center_sub_group' and doc_mm_yyyy = '$yyyy_mm' 
					GROUP BY comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group , budget_code ";
		$res		= mysqli_query($con,$sql);
		$r2  		= mysqli_fetch_array($res);
		$used_budget = $r2['used_budget'];
//echo $sql ."<BR>";
		$sql = " INSERT into dashboard_budget_data(comp_code , cost_center_group, budget_code,
					cost_center_sub_group, yyyy_mm , month_budget, used_budget ) 
			VALUES ('$comp_code' , '$cost_center_group', '$budget_code', '$cost_center_sub_group', '$yyyy_mm' , 
					'$april', '$used_budget') ";
		mysqli_query($con,$sql);
//echo $sql ."<BR>";
//exit();		
		$yyyy_mm				= '2021-05';
		$sql = "SELECT comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group, sum(total_value) as used_budget 
			FROM `dashboard_trans_data` 
			WHERE comp_code = '$comp_code' and trim(budget_code) = '$budget_code' 
				and trim(cost_center_group) = '$cost_center_group' 
				and trim(cost_center_sub_group) = '$cost_center_sub_group' and doc_mm_yyyy = '$yyyy_mm' 
					GROUP BY comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group , budget_code ";
		$res		= mysqli_query($con,$sql);
		$r2  		= mysqli_fetch_array($res);
		$used_budget = $r2['used_budget'];
		$sql = " INSERT into dashboard_budget_data(comp_code , cost_center_group,  budget_code,
					cost_center_sub_group, yyyy_mm , month_budget, used_budget ) 
			VALUES ('$comp_code' , '$cost_center_group', '$budget_code', '$cost_center_sub_group', '$yyyy_mm' , 
					'$may', '$used_budget') ";
		mysqli_query($con,$sql);
		
		$yyyy_mm				= '2021-06';
		$sql = "SELECT comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group, sum(total_value) as used_budget 
			FROM `dashboard_trans_data` 
			WHERE comp_code = '$comp_code' and trim(budget_code) = '$budget_code' 
				and trim(cost_center_group) = '$cost_center_group' 
				and trim(cost_center_sub_group) = '$cost_center_sub_group' and doc_mm_yyyy = '$yyyy_mm' 
					GROUP BY comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group , budget_code ";
		$res		= mysqli_query($con,$sql);
		$r2  		= mysqli_fetch_array($res);
		$used_budget = $r2['used_budget'];
		$sql = " INSERT into dashboard_budget_data(comp_code , cost_center_group,  budget_code,
					cost_center_sub_group, yyyy_mm , month_budget, used_budget ) 
			VALUES ('$comp_code' , '$cost_center_group', '$budget_code', '$cost_center_sub_group', '$yyyy_mm' , 
					'$june', '$used_budget') ";
		mysqli_query($con,$sql);
		

		$yyyy_mm				= '2021-07';
		$sql = "SELECT comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group, sum(total_value) as used_budget 
			FROM `dashboard_trans_data` 
			WHERE comp_code = '$comp_code' and trim(budget_code) = '$budget_code' 
				and trim(cost_center_group) = '$cost_center_group' 
				and trim(cost_center_sub_group) = '$cost_center_sub_group' and doc_mm_yyyy = '$yyyy_mm' 
					GROUP BY comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group , budget_code ";
		$res		= mysqli_query($con,$sql);
		$r2  		= mysqli_fetch_array($res);
//echo $sql ."<BR>";		
		$used_budget = $r2['used_budget'];
		$sql = " INSERT into dashboard_budget_data(comp_code , cost_center_group,  budget_code,
					cost_center_sub_group, yyyy_mm , month_budget, used_budget ) 
			VALUES ('$comp_code' , '$cost_center_group', '$budget_code', '$cost_center_sub_group', '$yyyy_mm' , 
					'$july', '$used_budget') ";
		mysqli_query($con,$sql);
//echo $sql ."<BR>";
//	exit();		

		$yyyy_mm				= '2021-08';
		$sql = "SELECT comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group, sum(total_value) as used_budget 
			FROM `dashboard_trans_data` 
			WHERE comp_code = '$comp_code' and trim(budget_code) = '$budget_code' 
				and trim(cost_center_group) = '$cost_center_group' 
				and trim(cost_center_sub_group) = '$cost_center_sub_group' and doc_mm_yyyy = '$yyyy_mm' 
					GROUP BY comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group , budget_code ";
		$res		= mysqli_query($con,$sql);
		$r2  		= mysqli_fetch_array($res);
		$used_budget = $r2['used_budget'];
		$sql = " INSERT into dashboard_budget_data(comp_code , cost_center_group,  budget_code,
					cost_center_sub_group, yyyy_mm , month_budget, used_budget ) 
			VALUES ('$comp_code' , '$cost_center_group', '$budget_code', '$cost_center_sub_group', '$yyyy_mm' , 
					'$august', '$used_budget') ";
		mysqli_query($con,$sql);
		

		$yyyy_mm				= '2021-09';
		$sql = "SELECT comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group, sum(total_value) as used_budget 
			FROM `dashboard_trans_data` 
			WHERE comp_code = '$comp_code' and trim(budget_code) = '$budget_code' 
				and trim(cost_center_group) = '$cost_center_group' 
				and trim(cost_center_sub_group) = '$cost_center_sub_group' and doc_mm_yyyy = '$yyyy_mm' 
					GROUP BY comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group , budget_code ";
		$res		= mysqli_query($con,$sql);
		$r2  		= mysqli_fetch_array($res);
		$used_budget = $r2['used_budget'];
		$sql = " INSERT into dashboard_budget_data(comp_code , cost_center_group,  budget_code,
					cost_center_sub_group, yyyy_mm , month_budget, used_budget ) 
			VALUES ('$comp_code' , '$cost_center_group', '$budget_code', '$cost_center_sub_group', '$yyyy_mm' , 
					'$september', '$used_budget') ";
		mysqli_query($con,$sql);
		

		$yyyy_mm				= '2021-10';
		$sql = "SELECT comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group, sum(total_value) as used_budget 
			FROM `dashboard_trans_data` 
			WHERE comp_code = '$comp_code' and trim(budget_code) = '$budget_code' 
				and trim(cost_center_group) = '$cost_center_group' 
				and trim(cost_center_sub_group) = '$cost_center_sub_group' and doc_mm_yyyy = '$yyyy_mm' 
					GROUP BY comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group , budget_code ";
		$res		= mysqli_query($con,$sql);
		$r2  		= mysqli_fetch_array($res);
		$used_budget = $r2['used_budget'];
		$sql = " INSERT into dashboard_budget_data(comp_code , cost_center_group,  budget_code,
					cost_center_sub_group, yyyy_mm , month_budget, used_budget ) 
			VALUES ('$comp_code' , '$cost_center_group', '$budget_code', '$cost_center_sub_group', '$yyyy_mm' , 
					'$october', '$used_budget') ";
		mysqli_query($con,$sql);
		

		$yyyy_mm				= '2021-11';
		$sql = "SELECT comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group, sum(total_value) as used_budget 
			FROM `dashboard_trans_data` 
			WHERE comp_code = '$comp_code' and trim(budget_code) = '$budget_code' 
				and trim(cost_center_group) = '$cost_center_group' 
				and trim(cost_center_sub_group) = '$cost_center_sub_group' and doc_mm_yyyy = '$yyyy_mm' 
					GROUP BY comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group , budget_code ";
		$res		= mysqli_query($con,$sql);
		$r2  		= mysqli_fetch_array($res);
		$used_budget = $r2['used_budget'];
		$sql = " INSERT into dashboard_budget_data(comp_code , cost_center_group,  budget_code,
					cost_center_sub_group, yyyy_mm , month_budget, used_budget ) 
			VALUES ('$comp_code' , '$cost_center_group', '$budget_code', '$cost_center_sub_group', '$yyyy_mm' , 
					'$november', '$used_budget') ";
		mysqli_query($con,$sql);
		

		$yyyy_mm				= '2021-12';
		$sql = "SELECT comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group, sum(total_value) as used_budget 
			FROM `dashboard_trans_data` 
			WHERE comp_code = '$comp_code' and trim(budget_code) = '$budget_code' 
				and trim(cost_center_group) = '$cost_center_group' 
				and trim(cost_center_sub_group) = '$cost_center_sub_group' and doc_mm_yyyy = '$yyyy_mm' 
					GROUP BY comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group , budget_code ";
		$res		= mysqli_query($con,$sql);
		$r2  		= mysqli_fetch_array($res);
		$used_budget = $r2['used_budget'];
		$sql = " INSERT into dashboard_budget_data(comp_code , cost_center_group,  budget_code,
					cost_center_sub_group, yyyy_mm , month_budget, used_budget ) 
			VALUES ('$comp_code' , '$cost_center_group', '$budget_code', '$cost_center_sub_group', '$yyyy_mm' , 
					'$december', '$used_budget') ";
		mysqli_query($con,$sql);
		

		$yyyy_mm				= '2022-01';
		$sql = "SELECT comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group, sum(total_value) as used_budget 
			FROM `dashboard_trans_data` 
			WHERE comp_code = '$comp_code' and trim(budget_code) = '$budget_code' 
				and trim(cost_center_group) = '$cost_center_group' 
				and trim(cost_center_sub_group) = '$cost_center_sub_group' and doc_mm_yyyy = '$yyyy_mm' 
					GROUP BY comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group , budget_code ";
		$res		= mysqli_query($con,$sql);
		$r2  		= mysqli_fetch_array($res);
		$used_budget = $r2['used_budget'];
		$sql = " INSERT into dashboard_budget_data(comp_code , cost_center_group,  budget_code,
					cost_center_sub_group, yyyy_mm , month_budget, used_budget ) 
			VALUES ('$comp_code' , '$cost_center_group', '$budget_code', '$cost_center_sub_group', '$yyyy_mm' , 
					'$january', '$used_budget') ";
		mysqli_query($con,$sql);
		

		$yyyy_mm				= '2022-02';
		$sql = "SELECT comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group, sum(total_value) as used_budget 
			FROM `dashboard_trans_data` 
			WHERE comp_code = '$comp_code' and trim(budget_code) = '$budget_code' 
				and trim(cost_center_group) = '$cost_center_group' 
				and trim(cost_center_sub_group) = '$cost_center_sub_group' and doc_mm_yyyy = '$yyyy_mm' 
					GROUP BY comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group , budget_code ";
		$res		= mysqli_query($con,$sql);
		$r2  		= mysqli_fetch_array($res);
		$used_budget = $r2['used_budget'];
		$sql = " INSERT into dashboard_budget_data(comp_code , cost_center_group,  budget_code,
					cost_center_sub_group, yyyy_mm , month_budget, used_budget ) 
			VALUES ('$comp_code' , '$cost_center_group', '$budget_code', '$cost_center_sub_group', '$yyyy_mm' , 
					'$february', '$used_budget') ";
		mysqli_query($con,$sql);
		

		$yyyy_mm				= '2022-03';
		$sql = "SELECT comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group, sum(total_value) as used_budget 
			FROM `dashboard_trans_data` 
			WHERE comp_code = '$comp_code' and trim(budget_code) = '$budget_code' 
				and trim(cost_center_group) = '$cost_center_group' 
				and trim(cost_center_sub_group) = '$cost_center_sub_group' and doc_mm_yyyy = '$yyyy_mm' 
					GROUP BY comp_code, doc_mm_yyyy, cost_center_group, cost_center_sub_group , budget_code ";
		$res		= mysqli_query($con,$sql);
		$r2  		= mysqli_fetch_array($res);
		$used_budget = $r2['used_budget'];
		$sql = " INSERT into dashboard_budget_data(comp_code , cost_center_group,  budget_code,
					cost_center_sub_group, yyyy_mm , month_budget, used_budget ) 
			VALUES ('$comp_code' , '$cost_center_group', '$budget_code', '$cost_center_sub_group', 
			'$yyyy_mm' , '$march', '$used_budget') ";
		mysqli_query($con,$sql);
		echo mysqli_error($con);
		//exit();
		
	}

echo "<script>window.close();</script>";	
exit();
	
?>

