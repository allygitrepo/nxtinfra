<?php
	$message  = '';
	$message .=  "<BR><b> Comment History</b>";	
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; background: #E7E7E7; font-size: 13px;' border='1' >";		
	$message .= "<tr><th style='width: 75%;text-align: left;'>Comment </th>
					<th style='width: 10%;text-align: left;'>Comment by </th>
					<th style='width: 15%;text-align: center;'> Comment Date Time </th>				
					</tr></table>";
		$message .= "<table cellspacing='0' border='.3' style='width: 95%; border: solid 0px black;  font-size: 10pt;' > ";		
	
		$sql 	= " SELECT a.comment_by, a.comment_datetime, a.comment, b.username 
				FROM `sma_comment` a, `sma_user` b 
				WHERE a.doc_type = '$doctype' AND a.doc_id = '$ap_id' AND b.id = a.comment_by
				ORDER BY a.id ";	

		$bs 	= mysqli_query($con,$sql);
		$rowaffect 	= mysqli_affected_rows($con);
		while($bs1 	= mysqli_fetch_array($bs)){
			$comment 			= $bs1['comment'];
			$comment_by 		= $bs1['username'];
			$comment_date 		= date('d-m-Y h:i:sa', strtotime($bs1['comment_datetime']));
			$message .= "<tr>
					<td style='width: 75%;text-align: left;'>". $comment . " </td>
					<td style='width: 10%;text-align: left;'>". $comment_by . " </td>
					<td style='width: 15%;text-align: center;'>" . $comment_date . " </td>				
					</tr> ";				
		}
		$message .= "</table>";
		
		if($rowaffect==0){
			$message='';
		}
		
	$body .= $message;
	
?>	