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
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='2'> Details Required for Flight Ticket Bookings </th></tr></table>";		
													
		
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 12pt;'>";
				
		$id				= $_GET['id'];
		$travel_id		= $_GET['id'];
	//$comid = $_SESSION['comid'];	
	
	$sql = "SELECT * from sma_traval_approval where id = '$id' ";
	
//echo $sql."<BR>";

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
			$emp_id			= $row['emp_id'];
			$emp_id			= $row['onbehalf_emp_id'];
			$company_id		= $row['company_id'];
			$dated			= date('Y-m-d', strtotime($row['dated']));
			$start_date		= date('Y-m-d', strtotime($row['start_date']));
			$end_date		= date('Y-m-d', strtotime($row['end_date']));
			$advance_amount	= $row['advance_amount'];
			$purpose_visit	= $row['purpose_visit'];
			$traval_from	= $row['traval_from'];
			$traval_to		= $row['traval_to'];
			$estimated_days	= $row['estimated_days'];
			$advance_amount	= $row['advance_amount'];
			$remarks		= $row['remarks'];
			$start_time		= $row['start_time'];
			$end_time		= $row['end_time'];
			$booking_details= $row['booking_details'];
			$airline_frequent_no	= $row['airline_frequent_no'];
		
		$s="select * from sma_user where id = '$emp_id' ";
	
		$sql = mysqli_query($con, $s);
		$rowcount = mysqli_num_rows($sql);
		
		while($r = mysqli_fetch_object($sql)){
			
			$userid		 	= $r->userid;
			$user_name 		= $r->username;
			$id		 		= $r->id;
			$pan_no	 		= $r->pan_no;
			$role	 		= $r->role;
			$department	 	= $r->department;
			$phone		 	= $r->phone;
			$mobile		 	= $r->mobile_no;
			$user_email	 	= $r->email;
			$user_company_id 	= $r->company_id;
			$user_category 	= $r->user_category;
			$password_expired_date	= $r->password_expired_date;
			
		}
		
		
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$comp_name 		= $r1['comp_name'];
		
		//$message .= "<tr><td>Salutation</td><td></td></tr>";
		$message .= "<tr><td>Travel Request No.</td><td style='text-align: left;'>$travel_id</td></tr>";
		$message .= "<tr><td>First Name</td><td>$user_name</td></tr>";
		$message .= "<tr><td>Mobile Number</td><td style='text-align: left;'>$mobile</td></tr>";
		$message .= "<tr><td>Email Id(Official)</td><td>$user_email</td></tr>";
	//	$message .= "<tr><td>Airline Frequent Filter No.(If Any)</td><td>$airline_frequent_no</td></tr>";
						
		$message .= "<tr><td>Departure Date</td><td style='text-align: left;'>$start_date</td></tr>";
		$message .= "<tr><td>Approx.Departure Time</td><td style='text-align: left;'>$start_time</td></tr>";
		$message .= "<tr><td>Departure From (City Name)</td><td>$traval_from</td></tr>";
		$message .= "<tr><td>Arrival To (City Name)</td><td>$traval_to</td></tr>";
						
		$message .= "<tr><td>Return Date</td><td style='text-align: left;'>$end_date</td></tr>";
		$message .= "<tr><td>Approx.Departure Time</td><td style='text-align: left;'>$end_time</td></tr>";
		$message .= "<tr><td>Departure From (City Name)</td><td>$traval_to</td></tr>";
		$message .= "<tr><td>Arrival To (City Name)</td><td>$traval_from</td></tr>";
		//$message .= "<tr><td>PAN No.</td><td>$pan_no</td></tr>";
		$message .= "<tr><td>Billing Entity</td><td>$comp_name</td></tr>";
		
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
    
	if($prn=='excel'){
		$fl_name = 'travel_req_export.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
	}

    // convert to PDF
	if($prn=='pdf'){
	//$baseurl
		$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once($dirname.'/html2pdf/html2pdf.class.php');
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
