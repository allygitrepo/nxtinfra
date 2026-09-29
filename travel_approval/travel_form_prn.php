<?php
if($_GET['sub'] == 'pdf'){
	session_start();
	include "../dbcon.php";
	include "../baseurl.php";

//echo dirname(__FILE__);
//exit();

/**
 * HTML2PDF Librairy - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */
	//$message="<table><tr><td>Table</td></tr></table>";

	$prn		= "excel";
//	$from_date	= date('Y-m-d', strtotime($_POST['from_date']));
//	$to_date	= date('Y-m-d', strtotime($_POST['to_date']));
//	$supplier_id= $_POST['supplier_id'];	
//	$company_id= $_POST['company_id'];
		
	$message ='';
	
	$message .= "<br><br>";
	
	//$message .= "<table border='1' cellspacing='0' style='width: 95%;margin-left: 30px;'>";
	$message .= '<div style="width:100%;">
					<div style="margin-left:45px;margin-right:45px;">';
    
	
	$message .= "<table border='0' cellspacing='0' style='width: 100%; text-align: center; font-size: 14pt;'>
			<tr><th style='width: 100%;'> Tour Approval / Advance Request Form </th></tr></table>";		
	$mtable = "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 12pt;'>";
				
	$id				= $_GET['id'];
	//$comid = $_SESSION['comid'];	
	
	$sql = "SELECT * from sma_traval_approval where id = '$id' ";
	
//echo $sql."<BR>";

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
			$emp_id			= $row['emp_id'];
			$req_id			= $row['id'];
			$company_id		= $row['company_id'];
			$dated			= date('d-m-Y', strtotime($row['dated']));
			$start_date		= date('d-m-Y', strtotime($row['start_date']));
			$end_date		= date('d-m-Y', strtotime($row['end_date']));
			$advance_amount	= $row['advance_amount'];
			$purpose_visit	= $row['purpose_visit'];
			$traval_from	= $row['traval_from'];
			$traval_to		= $row['traval_to'];
			$estimated_days	= $row['estimated_days'];
			$remarks		= $row['remarks'];
			$start_time		= $row['start_time'];
			$end_time		= $row['end_time'];
			$booking_details= $row['booking_details'];
			$purpose_visit	= $row['purpose_visit'];
			
			$draft_by		= $row['draft_by'];
		
		$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$comp_name 		= $r1['comp_name'];
		
		$s="select * from sma_user where id = '$emp_id' ";
//echo $s."<br>";	
		$sql = mysqli_query($con, $s);
		$rowcount = mysqli_num_rows($sql);
		
		while($r = mysqli_fetch_object($sql)){
			
			$userid		 	= $r->userid;
			$user_name 		= $r->username;
			$role	 		= $r->role;
			$pan_no	 		= $r->pan_no;
			$roll_no	 	= $r->roll_no;
			$dept	 		= $r->department;
			$designation 	= $r->designation;
			$level		 	= $r->level_id;
			$mobile		 	= $r->mobile;
			$user_email	 	= $r->email;
			$hod_id		 	= $r->level_1;
			$company_id 	= $r->company_id;
			$user_category 	= $r->user_category;
			$password_expired_date	= $r->password_expired_date;
			
		}
		
		
		if(empty($hod_id)){
			$hod_id=0;
		}
		$sql  = "SELECT * from sma_department where id = '$dept' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$department 		= $r1['name'];
		
		$sql  = "SELECT * FROM `sma_user` where id in ($hod_id)";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$rep_manager 		= $r1['username'];
		
		$sql  = "SELECT * from sma_designation where id = '$designation' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$designation 		= $r1['designation'];
		
		$sql  = "SELECT * from sma_level where id = '$level' ";
//echo $sql."<br>";
//exit();
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$level_name 		= $r1['level_name'];
	
		$sql = " SELECT * FROM `workflow_history` where doc_type = 'TA' and doc_id = '$id' and status = 'Draft' ";
//echo $sql."<br>";
		$res = mysqli_query($con, $sql);
		$rr = mysqli_fetch_object($res);
		$draft_by		= $rr->create_by;
		$draft_time		= $rr->create_date;
//echo $draft_time. "<BR>";		
		$draft_time	 	= date('d-m-Y H:i a', strtotime($draft_time));
//echo $draft_time. "<BR>";		
		
		$sql  = "select * from sma_user where id = '$draft_by' ";
//echo $sql."<br>";	
		$res = mysqli_query($con, $sql);
		$rr = mysqli_fetch_object($res);
		$draft_by		= $rr->username;
		
		$sql = " SELECT * FROM `workflow_history` where doc_type = 'TA' and doc_id = '$id' and status = 'Submitted' ";
//echo $sql."<br>";		
		$res = mysqli_query($con, $sql);
		$rr  = mysqli_fetch_object($res);
		$create_by			= $rr->create_by;
		$approved_time		= $rr->create_date;
		$approved_date		= date('d-m-Y', strtotime($rr->create_date));
		$approved_time	 	= date('d-m-Y H:i a', strtotime($approved_time));
		
		if($approved_date=='01-01-1970'){$approved_time='';}
		
		$sql  = "select * from sma_user where id  = '$create_by' ";
//echo $sql."<br>";	
//exit();
		
		$res = mysqli_query($con, $sql);
		$rr = mysqli_fetch_object($res);
		$approved_by		= $rr->username;
		
		//echo $approved_by. ' ' . 
		$message .= "<br><br>";
		$message .= "<table style='width: 100%;'> <tr><td style='width: 70%;'><b>Company Name</b>: <b>$comp_name</b> (to be Debited)</td><td>&nbsp;</td></tr></table>";
		$message .= "<br>";
		$message .= "<table style='width: 100%;'> <tr><td style='width: 50%;'><b>Request No.</b>: $req_id</td><td><b>Date</b> : $dated</td></tr></table>";
		$message .= "<br>";
		$message .= "<table style='width: 100%;'> <tr><td style='width: 50%;'><b>Employee Name</b>: $user_name</td><td><b>Employee Code</b>: $roll_no</td></tr></table>";
		$message .= "<br>";
		$message .= "<table style='width: 100%;'> <tr><td style='width: 50%;'><b>Designation</b>: $designation</td><td style='width: 50%;'><b>Level</b>: $level_name</td></tr></table>";
		$message .= "<br>";
		$message .= "<table style='width: 100%;'> <tr><td style='width: 50%;'><b>Department</b>: $department</td><td>Reporting Manager: $rep_manager</td></tr></table>";
		$message .= "<br>";
		$message .= "<table style='width: 100%;'> <tr><td style='width: 50%;'><b>Purpose of Vsit</b>: $purpose_visit</td><td></td></tr></table>";
		$message .= "<br>";
		$message .= "<table style='width: 100%;'> <tr><th style='width: 50%;'><b>Travel Details</b></th></tr></table>";
		
		$message .= "<table style='width: 100%;' border='.2' cellspacing='0' > <tr><td style='width: 20%;'>From</td><td style='width: 20%;'>To</td><td style='width: 20%;'><b>From Date</b></td><td style='width: 20%;'><b>To Date</b></td><td style='width: 20%;'><b>No of Days</b></td></tr></table>";
		
		$message .= "<table style='width: 100%;'  border='.2' cellspacing='0' > <tr><td style='width: 20%;'>$traval_from</td><td style='width: 20%;'>$traval_to</td>";
		$message .= "<td style='width: 20%;'>$start_date</td><td style='width: 20%;'>$end_date</td>";
		$message .= "<td style='width: 20%;'>$estimated_days</td></tr></table>";
		
		
		$message .= "<br>";				
		$message .= "<table style='width: 100%;text-align: left; '> <tr><td style='width: 30%;'><b>Advance Required</b> : Rs.$advance_amount/-</td><td style='width: 50%;'></td></tr></table>";
		
		
		
		if(!empty($remarks)){
			$message .=  "<p> Remarks:". $remarks. "</p>";
		}
		
		
		$message .=  "<h4> Documents</h4>";
	
	$modulePath = "travel_approval/";
	$baseurl2  = $baseurl.$modulePath;
	
		$id		= $_GET['id'];
		$sql 	= "SELECT * FROM file_uploads where module = 'TA' and reference_id = '$id'";
	
		$result = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		while($row = mysqli_fetch_array($result)){
			$file_name = $row['file_name'];
			$file_path = $row['file_path'];
			$doc_type = $row['doc_type'];
			
			$baseurl1 = $baseurl2.$file_path.'/'.$file_name;
			
			$message .= "<table cellspacing='2' style='width: 95%; border: solid 1px black; margin-left:0px; font-size: 12px;' >
				<tr><td style='width: 100%;text-align: left;font-size:12px;'><a href='$baseurl1'>$baseurl1</a></td></tr></table>";
		
		}

	}
	
	$message .=  "<h4> Approval Process</h4>";	
	$id		= $_GET['id'];
		
$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; background: #E7E7E7; font-size: 13px;' border='1' >";		
$message .= "<tr>
					<th style='width: 35%;text-align: left;'>Decision by </th>
					<th style='width: 25%;text-align: left;'>Status </th>
					<th style='width: 40%;text-align: center;'> Date Time </th>				
					</tr></table>";			

$message .= "<table cellspacing='0' border='.3' style='width: 95%; border: solid 0px black;  font-size: 10pt;' > ";				
//	if($status!='Draft'){
		$sql 	= "SELECT a.create_by, a.create_date, a.status, b.username FROM `workflow_history` a, `sma_user` b where a.doc_type = 'TA' and a.doc_id = '$id' and b.id = a.create_by order by a.id ";
		$bs 	= mysqli_query($con,$sql);
		while($bs1 	= mysqli_fetch_array($bs)){
			
			$status 		= $bs1['status'];
			$approval 		= $bs1['username'];
			$approval_date 	= date('d-m-Y h:i:sa', strtotime($bs1['create_date']));
			
			$message .= "<tr>
					<td style='width: 35%;text-align: left;'>". $approval . " </td>
					<td style='width: 25%;text-align: left;'>". $status . " </td>
					<td style='width: 40%;text-align: center;'>" . $approval_date . " </td>				
					</tr> ";			
			
		}
			
		$message .= "</table>";

    $message .= '</div></div>';
	
//echo $message;
//exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    
	
	//$baseurl
		$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once('../html2pdf/html2pdf.class.php');
		try
		{
			$fl_name  = 'travel_req_export.pdf';
			$html2pdf = new HTML2PDF('P', 'A4', 'fr');
			$html2pdf->pdf->SetDisplayMode('fullpage');
	//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
		   // $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$html2pdf->writeHTML($message);
			$html2pdf->Output($fl_name);
			
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
		
}

		
function moneyFormatIndia($num){
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
				$nums[1] = '';
			}
			$thecash = '';
		}
		else{
			$thecash = $thecash.".".$nums[1]; // with decimal eg. 123.12
			//$thecash = $thecash; // without decimal eg. 123
		}
        
		return $thecash;
    }
}
