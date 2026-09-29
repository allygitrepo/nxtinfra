<?php
	session_start();
	
	include "../dbcon.php";
	include "../baseurl.php";

//echo dirname(__FILE__);
//exit();

	$message ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='7'> Audit Log </th></tr></table>";		
	
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><th>Audit Date Time</th>
					<th>Company</th>
					<th>User Name</th>
					<th style='text-align:left;'>Main Menu</th>
					<th>Sub Menu</th>
					<th>Details</th>
					<th style='text-align:left;'>Action Taken</th>
					
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";
			/* 	
	$tableName	= "log_tbl";
	
	$sql 		= " SELECT * FROM $tableName order by username ";
	 */
	$sql = $_SESSION['sql'];
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
	
		$company_name = $row['company_name'];
		$sql = "SELECT * FROM company where 1 and comp_id = '$company_name' ";
        $res = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($res);
		$comp_code = $r2['comp_code'];
		
		$message .= "<tr>
					    <td>". $row['audit_date_time'] ."</td>
                        <td>". $comp_code ."</td>
						<td>". $row['user_name'] ."</td>
                        <td>". $row['main_menu'] ."</td>
                        <td>". $row['sub_menu'] ."</td>
                        <td>". $row['description']."</td>
                        <td>". $row['action']."</td>
					</tr>";

		}
	
	$message .= "</table>";
	
	//echo $message;
	//exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    
		$fl_name = 'audit_log_export.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
	
