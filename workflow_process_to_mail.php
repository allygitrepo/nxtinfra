<?php

	$message .=  "<table style='margin-left:10px;'><tr><td ><b>Approval History</b></td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 95%; margin-left:10px; border: solid 0px black; text-align: left; background: #E7E7E7; font-size: 13px;' border='.3' >";		
	$message .= "<tr><th style='width: 35%;text-align: left;'>Decision by </th>
					<th style='width: 25%;text-align: left;'>Status </th>
					<th style='width: 40%;text-align: center;'> Date Time </th>				
					</tr></table>";
		$message .= "<table cellspacing='0' border='.3' style='width: 95%; margin-left:10px; border: solid 0px black;  font-size: 10pt;' > ";				
		$sql 	= "SELECT a.create_by, a.create_date, a.status, b.username FROM `workflow_history` a, `sma_user` b where a.doc_type = '$doctype' and a.doc_id = '$ap_id' and b.id = a.create_by order by a.id ";
		$bs 	= mysqli_query($con,$sql);
		while($bs1 	= mysqli_fetch_array($bs)){
			$status 		= $bs1['status'];
			$approval 		= $bs1['username'];
			$create_by		= $bs1['create_by'];
			$approval_date 	= date('d-m-Y h:i:sa', strtotime($bs1['create_date']));
			
			
			if($doctype=='TE' || $doctype=='RE'){
				if($approver_1 == $create_by ){
				$status				= 'Verified';
				}
				if($approver_2 == $create_by ){
					$status				= 'Confirmed';
				}
			}
				
				
			$message .= "<tr>
					<td style='width: 35%;text-align: left;'>". $approval . " </td>
					<td style='width: 25%;text-align: left;'>". $status . " </td>
					<td style='width: 40%;text-align: center;'>" . $approval_date . " </td>				
					</tr> ";				
		}
		$message .= "</table>";
//	$body .= $message;
	
?>	