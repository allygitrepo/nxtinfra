<?php if($_GET['sub'] == 'list'){ 
include("../header.php");
$modulePath = "approval/";
?>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Workflow Report 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Workflow Report</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
					
						<form class="form-horizontal" action="hc_workflow_sum.php?sub=pdf" method="post">
                      
							<div class="form-group">
								
								<div class="col-sm-2">
									<label for="project" class="control-label ">Document Type</label>
									<select class="form-control " name="doc_type" id="doc_type"  >
										<option value=""> Select </option>
										<option value="AP"> Approvam Memo </option>
										<option value="PO"> Purchase Order </option>
										<option value="SI"> Supplier Invoice </option>
										<option value="CE"> Company Expense </option>
										<option value="TE"> Travel Expense </option>
										<option value="RE"> Reimbursement </option>
										<option value="PY"> Payment </option>
										<option value="BT"> Budget Transfer </option>
									</select>		
								</div>
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
								
								<div class="col-xs-2">
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
	
	$doc_type	= $_POST['doc_type'];
	$statusa	= $_POST['statusa'];
	$start_date	= date('Y-m-d', strtotime($_POST['start_date']));
	$end_date	= date('Y-m-d', strtotime($_POST['end_date']));
	
//	$supplier_id= $row['supplier_id'];	
//	$company_id= $row['company_id'];
		
	$message ='';
	
	if($doc_type=='AP'){
		$docdesc = 'Approval Memo';
	}
	else if($doc_type=='PO'){
		$docdesc = 'Purchase Order';
	}
	else if($doc_type=='SI'){
		$docdesc = 'Supplier Invoice';
	}
	else if($doc_type=='CE'){
		$docdesc = 'Company Expense';
	}
	else if($doc_type=='TE'){
		$docdesc = 'Travel Expense';
	}
	else if($doc_type=='RE'){
		$docdesc = 'Reimbursement';
	}
	else if($doc_type=='BT'){
		$docdesc = 'Budget Transfer';
	}
	else if($doc_type=='PY'){
		$docdesc = 'Payment';
	}
	
//Created by => Created date => Sent date => To Checker name – Approved date => To PM name / PI name => Approved date => To CXO name => Approved date

	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='16'>". $docdesc. " Transaction Workflow </th></tr></table>";		
						
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 5%;'><b>Doc Type</b></td>
					<td style='width: 5%;'><b>Doc ID</b></td>
					<td style='width: 10%;'><b>Created Date</b></td>	
					<td style='width: 10%;'><b>Company</b></td>
					<td style='width: 10%;'><b>Vendor Name</b></td>
					<td style='width: 10%;'><b>Subject</b></td>
					
					<td style='width: 10%;'><b>Send By</b></td>
					<td style='width: 10%;'><b>Approver Name</b></td>
					<td style='width: 10%;'><b>Approved Date</b></td>		
					
					<td style='width: 5%;'><b>Day</b></td>
					<td style='width: 5%;'><b>Final Status</b></td>
					<td style='width: 5%;'><b>Final Approver Name</b></td>
					
					
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 12pt;'>";
				
		$id		= $_GET['id'];
		
	$comid = $_SESSION['comid'];	
	
	//$doc_type = 'AP';
	
	if($doc_type == 'AP'){
		$sql = " SELECT *, company as company_id FROM `sma_approval_memo` where status not in ('Draft') and del != 'Y' and dated >= '$start_date' and dated <= '$end_date'  order by id ";
	}
	else if($doc_type == 'PO'){
		$sql = " SELECT *, project as company_id, to_supplier as supplier_id FROM `sma_purchase_order` where status not in ('Draft') and del != 'Y' and dated >= '$start_date' and dated <= '$end_date' order by id ";
	}
	else if($doc_type == 'SI'){
		$sql = " SELECT *, suplier_name as supplier_id FROM `sma_supplier_invoice` where status not in ('Draft') and del != 'Y' and created_date >= '$start_date' and created_date <= '$end_date'  order by id ";
	}
	else if($doc_type == 'CE'){
		$sql = " SELECT *, emp_id as supplier_id FROM `sma_travel_expenses` where status not in ('Draft') and del != 'Y' 
				and exp_type= 'C' and dated >= '$start_date' and dated <= '$end_date'  order by id ";
	}
	else if($doc_type == 'TE'){
		$sql = " SELECT * , onbehalf_emp_id as supplier_id FROM `sma_travel_expenses` where status not in ('Draft') and del != 'Y' 
				and exp_type= 'T' and dated >= '$start_date' and dated <= '$end_date'  order by id ";
	}
	else if($doc_type == 'RE'){
		$sql = " SELECT *, onbehalf_emp_id as supplier_id FROM `sma_travel_expenses` where status not in ('Draft') and del != 'Y' 
				and exp_type= 'R' and dated >= '$start_date' and dated <= '$end_date'  order by id ";
	}
	else if($doc_type == 'BT'){
		$sql = " SELECT *, project as company_id, remarks as subject FROM `budget_adjust_from_to` where status not in ('Draft') 
				 and dated >= '$start_date' and dated <= '$end_date'  order by id ";
	}
	else if($doc_type == 'PY'){
		$sql = " SELECT *, paid_date as dated, paid_to as supplier_id, remarks as subject FROM `payment_header` where status not in ('Draft') 
				 and paid_date >= '$start_date' and paid_date <= '$end_date'  order by id ";
	}
//echo $sql. "<BR>";	//and id between 1 and 10
	$result = mysqli_query($con, $sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$doc_id					= $row['id'];
	
		$draft_by 	 			= $row['draft_by'];
		$subject 	 			= $row['subject'];
		$company 				= $row['company_id'];
		$party_id 				= $row['supplier_id'];
		$status					= $row['status'];
		if($doc_type != 'PO'){
			//$draft_dated 			= date('d-m-Y h:i sa', strtotime($row['draft_dated']));
			$draft_dated 			= date('d-m-Y', strtotime($row['draft_dated']));
			$create_date_prev		= $row['draft_dated'];
		}
		else if($doc_type == 'PO'){
			$draft_dated 			= date('d-m-Y', strtotime($row['draft_date']));
			$create_date_prev		= $row['draft_date'];
		}
		
		if($doc_type == 'SI'){
			$draft_dated 			= date('d-m-Y', strtotime($row['created_date']));
			$create_date_prev		= $row['created_date'];
		}
		$st_flag = '';
		if($doc_type == 'PY'){
			
			$st_flag = $row['st_flag'];
			if($st_flag=='S'){
				$st_flag ='SI';
			}
			else if($st_flag=='A'){
				$st_flag ='TA';
			}
			else if($st_flag=='T'){
				$st_flag ='TE';
			}
			else if($st_flag=='C'){
				$st_flag ='OE';
			}
			else if($st_flag=='D'){
				$st_flag ='SA';
			}
			
		}
		
		$sql = "SELECT * from sma_user where id = '$draft_by' ";
		$com = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($com);
		$draft_by_name 			= $r2['username'];
		
		$approver_1_status 	= $row['approver_1_status'];
		$approver_2_status 	= $row['approver_2_status'];
		$approver_3_status 	= $row['approver_3_status'];
		$approver_4_status 	= $row['approver_4_status'];	
		$approver_5_status 	= $row['approver_5_status'];
		$approver_6_status 	= $row['approver_6_status'];
		$approver_7_status 	= $row['approver_7_status'];
		$approver_8_status 	= $row['approver_8_status'];
		
		if($approver_1_status=='Approved'){
			$approver_id 		= $row['approver_1'];
		}
		if($approver_2_status=='Approved'){
			$approver_id 		= $row['approver_2'];
		}
		if($approver_3_status=='Approved'){
			$approver_id 		= $row['approver_3'];
		}
		if($approver_4_status=='Approved'){
			$approver_id 		= $row['approver_4'];
		}
		if($approver_5_status=='Approved'){
			$approver_id 		= $row['approver_5'];
		}
		if($approver_6_status=='Approved'){
			$approver_id 		= $row['approver_6'];
		}
		if($approver_7_status=='Approved'){
			$approver_id 		= $row['approver_7'];
		}
		if($approver_8_status=='Approved'){
			$approver_id 		= $row['approver_8'];
		}
		
		$sql 	= "SELECT * FROM `sma_user` where id = '$approver_id'";
		$res = mysqli_query($con,$sql);
		$lc 	= mysqli_fetch_array($res);
		$approver_name    = $lc['username'];
		
		
		$sql = "SELECT * from company where comp_id = '$company' ";
		$com = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($com);
		$comp_code	 = $r2['comp_code'];
		
		$party_name 	= '';
		
		$sql = "SELECT * FROM sma_party_mst where id = '$party_id' ";
		$com = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($com);
		$party_name 	= $r2['party_name'];
		
		if($doc_type == 'RE' || $doc_type == 'TE'){
			$sql = "SELECT * from sma_user where id = '$party_id' ";
			$com = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($com);
			$party_name 			= $r2['username'];
		}
		
		$rowcount = 0;
		$sql = "SELECT * from sma_user where userid = '$draft_by' ";
		$com = mysqli_query($con, $sql);
		
		$r2 = mysqli_fetch_array($com);
		$draft_by	 = $r2['username'];
			
		
		$ij = 0;				
		$sql = " SELECT doc_type, doc_id, create_by, create_date, reviewed_by, approved_date,  status  FROM `workflow_history` 
					where reviewed_by >0 AND create_by > 0 
					AND doc_type = '$doc_type' AND doc_id = '$doc_id' AND status not in ( 'Draft' , 'Amend', 'Auto Closed', 'Closed', 'Accepted' , '' )";
		mysqli_query($con, $sql);
		$rwcnt = mysqli_affected_rows($con);
		
		$sql = " SELECT doc_type, doc_id, create_by, create_date, reviewed_by, approved_date,  status  FROM `workflow_history` 
					where reviewed_by >0 AND create_by > 0 
					AND doc_type = '$doc_type' AND doc_id = '$doc_id' AND status not in ( 'Draft' , 'Amend', 'Auto Closed', 'Closed', 'Accepted' , '' ) order by id ";
		$com = mysqli_query($con, $sql);
		$rowcount=mysqli_num_rows($com);
		$checker_name 			= '';
		$sent_date				= '';
		$checked_date			= '';
		while($r2 = mysqli_fetch_array($com)){
			
			$status_v					= $r2['status'];
			$create_by				= $r2['create_by'];
			$create_date			= date('d-m-Y', strtotime($create_date_prev));
			$checked_date			= date('d-m-Y', strtotime($r2['approved_date']));
			//$approved_date		= $r2['approved_date'];
			
			$datediff			 	= strtotime($checked_date) - strtotime($create_date);
			$checked_day 			= 0;
//echo $doc_id. ' <= '. strtotime($checked_date) . ' < - > ' . strtotime($create_date). ' ' .$datediff. ' <<>> ' . $checked_date . ' <<>> ' . $create_date ."<BR>";		
			if( date('d-m-Y', strtotime($checked_date)) != date('d-m-Y',strtotime($create_date)) ){
				$checked_day 		= round($datediff / (60 * 60 * 24));
			}
			
			$sql = "SELECT * from sma_user where id = '$create_by' ";
			$qry = mysqli_query($con, $sql);
			$r22 = mysqli_fetch_array($qry);
			$send_by 				= $r22['username'];
			
			$user_id				= $r2['reviewed_by'];
			$sql = "SELECT * from sma_user where id = '$user_id' ";
			$qry = mysqli_query($con, $sql);
			$r22 = mysqli_fetch_array($qry);
			$checker_name 			= $r22['username'];
		
			/* $sql = "SELECT * FROM `workflow_history` where doc_type = '$doc_type' and doc_id = '$doc_id' order by id desc ";
			$comresult 	= mysqli_query($con,$sql);
			$r2 		= mysqli_fetch_array($comresult);
			$final_approver_date	= date('d-m-Y', strtotime($r2['create_date']));
			 */
			 
			$ij = $ij + 1;;			
			if( ( $status=='Completed' || $status=='Auto Closed' || $status=='Closed' || $status =='Amend' ) && $rwcnt == $ij ){
				$status_a = 'Final Approver';
				$ij = 0;
			}	
			else {
				$status_a = $status_v;	
			}	
			
			if($doc_type=='PY'){
				$doc_id .= '-'.$st_flag;
			}
			
				$message .= "<tr>
						<td style='width: 5%;'>$doc_type</td>
						<td style='width: 5%;'>$doc_id</td>
						<td style='width: 10%;'>$draft_dated</td>
						
						<td style='width: 10%;'>$comp_code</td>
						
						<td style='width: 10%;'>$party_name</td>
						<td style='width: 10%;'>$subject</td>
						<td style='width: 10%;'>$send_by</td>
						<td style='width: 10%;'>$checker_name</td>
						<td style='width: 10%;'>$checked_date</td>
						
						<td style='width: 05%;'>$checked_day</td>
						
						<td style='width: 05%;'>$status_a</td>
						<td style='width: 05%;'>$approver_name</td>
						</tr>
						";
			
			$create_date_prev		= $r2['create_date'];

		}
		
		
		
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
		$fl_name = 'workflow_'.$doc_type. '_' .date('d-m-Y').'.xls';
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




		
