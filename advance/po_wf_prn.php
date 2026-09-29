<?php
if($_GET['sub'] == 'pdf'){
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
	if(empty($_GET['id'])){
		echo "<script>alert('File not found...');window.close();</script>";
		return;
	}
	
	$prn		= $_GET['sub'];
	$id			= $_GET['id'];
	$comp_id	= $_GET['comp_id'];	
	$location   = $_GET['location'];
	
	$sql 	= "SELECT * FROM `sma_location` where id = '$location'";	
//echo $sql; 
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$loc_name    = $row['loc_name'];
		$loc_addr1   = $row['loc_addr1'];
		$loc_addr2   = $row['loc_addr2'];
		$loc_addr3   = $row['loc_addr3'];
		$loc_city    = $row['loc_city'];
		$loc_pincode = $row['loc_pincode'];
		$loc_phone   = $row['loc_phone'];
		$loc_mobile  = $row['loc_mobile'];
		$loc_email   = $row['loc_email'];
		$loc_pan_no  = $row['loc_pan_no'];
		$loc_gst_no  = $row['loc_gst_no'];
	}
	
$sql="SELECT * FROM `company` where comp_id = '$comp_id' ";
$comresult 	= mysqli_query($con,$sql);
if(!empty($error)){ echo "ERROR : " . $error; exit();}
	$error  			= mysqli_error($con);
	$com 				= mysqli_fetch_array($comresult);
	
	$comp_name 			= $com['comp_name'];
	$comp_addr1 		= $com['comp_addr1'];
	$comp_addr2 		= $com['comp_addr2'];
	$comp_addr3 		= $com['comp_addr3'];
	$comp_email 		= $com['comp_email'];
	$comp_office 		= $com['comp_office'];
	$comp_mobile 		= $com['comp_mobile'];
	$comp_city  		= $com['comp_city'];
	$comp_pincode 		= $com['comp_pincode'];
	$comp_country 		= $com['comp_country'];
	$comp_faxno 		= $com['comp_faxno'];
	$comp_cin_no 		= $com['comp_cin_no'];
	$comp_pan_no 		= $com['comp_pan_no'];
	
	$message ='';
	$message .= "<table border='1' cellspacing='0' style='width: 95%; border: solid 1px black; text-align: left; margin-left:10px;margin-right:10px;font-size: 12px;word-wrap:break-word;'>";
	$message .= '<tr><th width="10%" style="text-align:left;">Dated</th>
							<th width="10%" style="text-align:left;">By User</th>
							<th width="10%" style="text-align:left;">Decision</th>
							<th width="10%" style="text-align:left;">Send Dated</th>
							<th width="10%" style="text-align:left;">To User</th>
							<th width="10%" style="text-align:left;">Role</th>
							<th width="40%" style="text-align:left;">Remarks</th>
					</tr>';
						
	$id				= $_GET['id'];
	$sql 	= "SELECT * FROM workflow_history where doc_id = '$id' and doc_type = 'AV' order by id desc";
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($r1 = mysqli_fetch_array($result)){
	
		$id 				= $r1['id'];
												
		$status				= $r1['status'];
		$reviewed_by 		= $r1['reviewed_by'];
		$approved 			= $r1['approved'];
		$approved_date		= date('d-m-Y h:i:sa', strtotime($r1['approved_date']));
		$remarks 			= $r1['remarks'];
												
										
		$s2="SELECT * FROM sma_user where id = '$reviewed_by' ";
										//echo $s2. "<BR>";
												$r3 = mysqli_query($con, $s2);
												$rw1 = mysqli_fetch_array($r3);
												$reviewed_by = $rw1['username'];
										//echo $reviewed_by . " <<<<<BR>";
												
												$role = $rw1['role'];

												$sl="SELECT * FROM sma_role where id = '$role' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$role = $rw['role'];
												
												$create_by		= $r1['create_by'];
												$create_date	= date('d-m-Y h:i:sa', strtotime($r1['create_date']));
												
												$sl="SELECT * FROM sma_user where id = '$create_by' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$create_by = $rw['username'];
												
			$message .= '<tr><td width="10%" style="text-align:left;">'. $create_date.'</td>
							<td width="10%" style="text-align:left;">'. $create_by.'</td>
							<td width="10%" style="text-align:left;">'. $status.'</td>
							<td width="10%" style="text-align:left;">'. $approved_date.'</td>
							<td width="10%" style="text-align:left;">'. $reviewed_by.'</td>
							<td width="10%" style="text-align:left;">'. $role.'</td>
							<td width="40%" style="text-align:left;overflow: hidden; max-width: 400px;word-wrap: break-word;">'. $remarks.'</td>
						</tr>';
			
	}
	
	$message .= "</table>";
	
	
	//$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; margin-left:40px;font-size: 12px;'><tr>
	// <span style='margin-left:40px;'> $terms </span> </tr></table>";
	
	//$message .= "<div style='width: 675px;border: 0px solid red;padding: 1px;margin: 10px;'><span style='margin-left:10px;'> $terms </span></div>";
	

	$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; margin-left:10px;font-size: 10pt;'>
			<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";		
	
//	$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; font-size: 10pt;'>
//			<tr><td style='width: 95%;text-align: Center;'>This is a computer-generated document. No signature is required &nbsp; </td></tr></table>";		

//	$message .= "</div>";
	
	
//print $message;
//exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;

	
    // convert to PDF
	if($prn=='pdf'){
	//$baseurl
		$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once('../html2pdf/html2pdf.class.php');
		try
		{
			$fl_name = 'powfl'.$id. '.pdf';
			$html2pdf = new HTML2PDF('L', 'A4', 'fr');
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