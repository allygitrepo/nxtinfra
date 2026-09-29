<?php 

include "../dbcon.php";

	$sql= " SELECT  a.budget_id as budget_id, sum(a.amount) as amount, c.company_id FROM  `sma_expenses`  a, account_mst b , sma_travel_expenses c where c.id = a.approval_Ref_no and a.reference = b.id and status = 'Completed' and budget_id >0 and c.del!='Y'  group by a.budget_id, c.company_id ";
echo $sql. "<BR>";

	$result 	= mysqli_query($con, $sql);
	while($row = mysqli_fetch_array($result)){
		
		
		
		$budget_id		= $row['budget_id'];
		$amount  		= $row['amount'];
		
		$sql = "update `sma_budget` set used_budget = used_budget + '$amount' where id = '$budget_id' ";
//echo $sql. "<BR>";				
		$res= mysqli_query($con, $sql);
		echo mysqli_error($con);
				
		//exit();
		
	}	
				

	exit('Process over...');
	


?>


