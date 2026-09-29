<?php if($_GET['sub'] == 'list'){ 
include("../header.php");
$modulePath = "approval/";
?>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Purchase Order Workflow Report 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Purchase Order Workflow Report</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
					
						<form class="form-horizontal" action="hc_workflow_po.php?sub=pdf" method="post">
                      
							<div class="form-group">
								
								<div class="col-md-2">
									<label class="control-label">Start.Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="<?php echo $start_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<label class="control-label">End.Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="<?php echo $end_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
							
								<div class="col-sm-2">
									<label for="project" class="control-label">Status</label>
									<select class="form-control " name="statusa" id="statusa" >
										<option value=""> Select </option>
										<option value="Approved" > Approved </option>
										<option value="Rejected" > Rejected </option>
											
									</select>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-xs-4">
									<label for="project" class="control-label">&nbsp;</label>
								</div>
								
                                		
								<input class="btn btn-primary" type="submit" value="Submit" name="submit">&nbsp;&nbsp;&nbsp;
								<a href="dashboard.php?sub=list&" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;cancel</a>
								</div>
								
							</div>
						
						
				</form>
<?php 

	include("../footer.php");

 }
 
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
	$statusa	= $_POST['statusa'];
	$start_date	= date('Y-m-d', strtotime($_POST['start_date']));
	$end_date	= date('Y-m-d', strtotime($_POST['end_date']));
	
//	$supplier_id= $row['supplier_id'];	
//	$company_id= $row['company_id'];
		
	$message ='';
	
//Created by => Created date => Sent date => To Checker name – Approved date => To PM name / PI name => Approved date => To CXO name => Approved date

	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='16'> Purchase Order Workflow Summary </th></tr></table>";		
						
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 5%;'><b>Doc Type</b></td>
					<td style='width: 5%;'><b>Doc Id</b></td>
					<td style='width: 10%;'><b>Created Date</b></td>	
					
					<td style='width: 10%;'><b>Company</b></td>
					
					<td style='width: 10%;'><b>Vendor Name</b></td>
					<td style='width: 10%;'><b>Subject</b></td>
					<td style='width: 10%;'><b>Amount</b></td>
					
					<td style='width: 10%;'><b>Created By</b></td>
					<td style='width: 10%;'><b>Sent Date</b></td>
					
					<td style='width: 10%;'><b>Checker Name</b></td>
					<td style='width: 10%;'><b>Checked Date</b></td>		
					<td style='width: 5%;'><b>Day</b></td>
					
					<td style='width: 10%;'><b>Verified By</b></td>
					<td style='width: 10%;'><b>Verified Date</b></td>
					<td style='width: 10%;'><b>Days</b></td>
					
					<td style='width: 10%;'><b>Approved By</b></td>
					<td style='width: 10%;'><b>Approved Date</b></td>
					<td style='width: 10%;'><b>Days</b></td>
					<td style='width: 5%;'><b>Status</b></td>
					<td style='width: 5%;'><b>Total day</b></td>
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 12pt;'>";
				
		$id		= $_GET['id'];
		
	$comid = $_SESSION['comid'];	
	$sql = " SELECT doc_type, doc_id, create_by, create_date, status  FROM `workflow_history` where status = '$statusa' and doc_type = 'PO' and create_date >= '$start_date' and create_Date <= '$end_date' order by doc_type,  doc_id desc, id  ";
	// and doc_id in (1374, 1366, 1365,1355,1354,1351,1350, 1349, 1345, 1343) and doc_id > 1350 limit 1, 50
	//$sql = $_SESSION['sqlex'];
	
//echo $sql."<BR>";

//$now = time(); // or your date as well
//$your_date = strtotime("2019-11-31");
//$datediff = $now - $your_date;
//echo round($datediff / (60 * 60 * 24));

	$result = mysqli_query($con, $sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$doc_type				= $row['doc_type'];
		$doc_id					= $row['doc_id'];
		
		if($doc_id_prev	== $doc_id){ 
			continue;
		}
		
		$user_id				= $row['create_by'];
		$approved_date			= date('d-m-Y h:i sa', strtotime($row['create_date']));
		$approved_date_c		= $row['create_date'];
//		$checker				= $row['changed_by'];
		$status					= $row['status'];
		$sql = "SELECT * from sma_user where id = '$user_id' ";
		$com = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($com);
		$approval_name 			= $r2['username'];
		
		$rowcount=0;
		$sql = "SELECT * from sma_purchase_order where id = '$doc_id' and del != 'Y'";
		$com = mysqli_query($con, $sql);
		$rowcount=mysqli_num_rows($com);
		if($rowcount==0){
			continue;
		}
		$r2  = mysqli_fetch_array($com);
		$del 		 = $r2['del'];
		if($del=='Y'){
			continue;
		}	
		
		$draft_by 	 = $r2['draft_by'];
		$subject 	 = $r2['po_number'];
		$company 	 = $r2['project'];
		$vendor_id 	 = $r2['to_supplier'];
		$draft_dated = date('d-m-Y h:i sa', strtotime($r2['draft_date']));
		$datediff			= strtotime($row['create_date']) - strtotime($r2['draft_date']);
		
		//echo  strtotime($row['create_date']). ' - ' . strtotime($r2['draft_dated']). ' ' . $datediff. "<BR>";
		$approve_day 		= 0;
		if( date('d-m-Y', strtotime($row['create_date'])) != date('d-m-Y',strtotime($r2['draft_date'])) ){
			$approve_day 		= round($datediff / (60 * 60 * 24)) ;
		}
		
		$sql = "SELECT * from company where comp_id = '$company' ";
		$com = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($com);
		$comp_code	 = $r2['comp_code'];
		
		$party_name 	= '';
		$sql = "SELECT * FROM sma_party_mst where id = '$vendor_id' ";
		$com = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($com);
		$party_name 	= $r2['party_name'];
		
		$purchase_id = $doc_id;
		$app_amount = '';
		$sql="SELECT * from sma_po_items where purchase_id = '$purchase_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r1 = mysqli_fetch_array($res1)){
			$qty 	= $r1['quantity'];
			$rate 	= $r1['unit_rate'];
			$gst	= $r1['gst'];
			$amount = $qty * $rate + (($qty * $rate) * $gst / 100);
			$app_amount = $tot_amount + $amount;
		}
		
		$rowcount = 0;
		$sql = "SELECT * from sma_user where userid = '$draft_by' ";
		$com = mysqli_query($con, $sql);
		
		$r2 = mysqli_fetch_array($com);
		$draft_by	 = $r2['username'];
			
		$sql = " SELECT doc_type, doc_id, create_by, create_date, reviewed_by, approved_date,  status  FROM `workflow_history` 
					where doc_type = 'PO' and doc_id = '$doc_id' and status in ( 'Pending', 'Verified', 'Submited' )";
		$com = mysqli_query($con, $sql);
		$rowcount=mysqli_num_rows($com);
		$checker_name 			= '';
		$sent_date				= '';
		$checked_date			= '';
		if($rowcount > 0){
			$r2 = mysqli_fetch_array($com);
			$sent_date				= date('d-m-Y h:i sa', strtotime($r2['create_date']));
			$checked_date			= date('d-m-Y h:i sa', strtotime($r2['approved_date']));
			$checked_date_c			= $r2['approved_date'];
			
			$datediff			 	= strtotime($r2['approved_date']) - strtotime($r2['create_date']);
			$checked_day 			= 0;
			if( date('d-m-Y', strtotime($r2['approved_date'])) != date('d-m-Y',strtotime($r2['create_date'])) ){
				$checked_day 		= round($datediff / (60 * 60 * 24));
			}

			$user_id				= $r2['reviewed_by'];
			$sql = "SELECT * from sma_user where id = '$user_id' ";
			$com = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($com);
			$checker_name 			= $r2['username'];
		}
		
		$rowcount = 0;
		$sql = " SELECT doc_type, doc_id, create_by, create_date, reviewed_by, approved_date,  status  FROM `workflow_history` 
					where doc_type = 'PO' and doc_id = '$doc_id' and status in ( 'Verified' )";
					
//echo $sql. "<BR>";

		$com = mysqli_query($con, $sql);
		$rowcount=mysqli_num_rows($com);
		$verified_name 			= '';
		$verified_date			= '';
		$verified_day 			= 0;
		$approved_day			= 0;
		if($rowcount > 0){
			$r2 = mysqli_fetch_array($com);
			$verified_date				= date('d-m-Y h:i sa', strtotime($r2['create_date']));
			$verified_date_c			= $r2['create_date'];
			$user_id					= $r2['create_by'];
			$sql = "SELECT * from sma_user where id = '$user_id' ";
			$com = mysqli_query($con, $sql);
			$r3 = mysqli_fetch_array($com);
			$verified_name 			= $r3['username'];
			
			$datediff			 	= strtotime($verified_date_c) - strtotime($checked_date_c);
//echo $doc_id. ' ' . date('d-m-Y', strtotime($checked_date_c)) .' != '. date('d-m-Y',strtotime($approved_date_c)) . ' ' . $approved_date_c . "<BR>";
			$verified_day 			= 0;
			if( date('d-m-Y', strtotime($checked_date_c)) != date('d-m-Y',strtotime($verified_date_c)) ){
				$verified_day 		= round($datediff / (60 * 60 * 24));
			}
			
			$datediff			 	= strtotime($approved_date_c) - strtotime($verified_date_c);
			$approved_day 			= 0;
			if( date('d-m-Y', strtotime($verified_date_c)) != date('d-m-Y',strtotime($approved_date_c)) ){
				$approved_day 		= round($datediff / (60 * 60 * 24));
			}
			
		}
		else if(empty($verified_name) ) {
			
			$datediff			 	= strtotime($approved_date_c) - strtotime($checked_date_c);
			$approved_day 			= 0;
			if( date('d-m-Y', strtotime($checked_date_c)) != date('d-m-Y',strtotime($approved_date_c)) ){
				$approved_day 		= round($datediff / (60 * 60 * 24));
			}
			
		}	
		
			$message .= "<tr>
						<td style='width: 5%;'>$doc_type</td>
						<td style='width: 5%;'>$doc_id</td>
						<td style='width: 10%;'>$draft_dated</td>
						
						<td style='width: 10%;'>$comp_code</td>
						
						<td style='width: 10%;'>$party_name</td>
						<td style='width: 10%;'>$subject</td>
						<td style='width: 10%;text-align:right;'>$app_amount</td>
						
						<td style='width: 10%;'>$draft_by</td>
						<td style='width: 10%;'>$sent_date</td>
						
						<td style='width: 10%;'>$checker_name</td>
						<td style='width: 10%;'>$checked_date</td>
						<td style='width: 05%;'>$checked_day</td>
						
						<td style='width: 10%;'>$verified_name</td>
						<td style='width: 10%;'>$verified_date</td>
						<td style='width: 10%;'>$verified_day</td>
						
						<td style='width: 10%;'>$approval_name</td>
						<td style='width: 10%;'>$approved_date</td>
						<td style='width: 10%;'>$approved_day</td>
						
						
						<td style='width: 5%;'>$status</td>
						<td style='width: 5%;'>$approve_day</td>
						";
					$message .= "</tr>";
					
		$doc_id_prev				= $doc_id;
	
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
		$fl_name = 'workflow_po.xls';
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
			$fl_name  = 'budget_export.pdf';
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




		
