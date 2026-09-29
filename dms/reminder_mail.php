		
	<?php
	
		//include("../header.php");
		include("../dbcon.php");
		include('../baseurl.php');
	
		$modulePath = "dms/";
		$_SESSION['reset'] = '1';
		
		
		require '../PHPMailer-master/PHPMailerAutoload.php';
	
	?>

<?php

		$sql = " select * from  dms_inward where remind_me = 'Y' and stop_remind != 'Y' and reminder_date >= now() ";
echo $sql. "<BR>";
		//and reminder_date >=  and in_days >0 //and inward_no = 103639 
		$result = mysqli_query($con,$sql);
		while($row = mysqli_fetch_array($result)){
			$inward_no  	= $row['inward_no'];
			$reminder_date  = $row['reminder_date'];
			$in_days		= $row['in_days'];
			//$status  		= $row['status'];
			$today_date		= date("Y-m-d");
			$remarks		= $row['remarks'];
			
			//echo date('Y-m-d', strtotime($reminder_date. ' + 1 days')). ' ' ;
			//echo date('Y-m-d', strtotime($reminder_date. ' + 2 days')). "<BR>";
			$reminder_date = date('Y-m-d', strtotime($reminder_date. ' - '. $in_days .' days'));
	
echo $inward_no. ' ' .$reminder_date. ' <<>> ' .$in_days. ' <<>> '. $today_date .' <BR>' ;
		
			if($reminder_date <= $today_date){
				
				$sql = " select * from  my_documents a, sma_user b where a.current_user_id = b.id and a.reference_id = '$inward_no' ";	
			echo $sql;	//exit("STOPED...");
				$res = mysqli_query($con,$sql);
				while($r1 = mysqli_fetch_array($res)){
					
					$user_email		= $r1['email'];
					$username		= $r1['username'];
					echo $user_email . ' ' . $username . " Hello World <br> ";
					
					$baseurl1 = $baseurl.$modulePath.'my_document.php?sub=edit&inward_no='.$inward_no;
				//	include "send_mail.php";
					
				}
				
			}
			
		}
	
	echo "<script>window.close();</script>";	
	exit();
	
?>	