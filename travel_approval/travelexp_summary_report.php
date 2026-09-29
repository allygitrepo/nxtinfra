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
	$from_date	= date('Y-m-d', strtotime($_POST['from_date']));
	$to_date	= date('Y-m-d', strtotime($_POST['to_date']));
	$company_id = $_POST['company_id'];	
		
	$message  ='';
		
	$gtype		   = $_GET['gtype'];
		
	if($gtype =='R'){
		$d_type = 'Regular'	;
	}
	else if($gtype =='C'){
		$d_type = 'Company'	;
	}
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12px;'>
			<tr><th style='width: 100%;' colspan='11'> $d_type Expenses Register from ".$_POST['from_date']." TO ".$_POST['to_date']."</th></tr></table>";
	
	
	$head .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12px;'>
				<tr><th>#No.</th>
					<th>Name</th>
					<th>Dated</th>
					<th>App.Ref.No.</th>
					<th>Company</th>";
		
	$message .= $head."<th>Invoice No.</th>
					<th>Expenses</th>
					<th>Dated</th>
					<th>Amount</th>
					<th>Remarks</th>
					
					<th>Budget Name</th>
					<th>Budget Head</th>
					<th>Budget Amount</th>
					<th>Approver Name</th>
					<th>Workflow Type</th>
					</tr></table>";
					
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";

	
	$tableName	= "sma_travel_expenses";
	
//	$sql 		= " SELECT * FROM $tableName where exp_type = '$gtype' and dated >= '$from_date' and dated <= '$to_date' ";
	
/* 	if (!empty($company_id)){
		$sql  .= " and company_id = '$company_id' ";
	} */
	
	//if($gtype =='C'){
		$sql = $_SESSION['sqlreg'];
	//}
//echo $sql;
//exit();	
//	$sql = "SELECT * from sma_travel_expenses where exp_type = 'C' and company_id in ( 4,5,6,8,9,0 ) and emp_id !='' and company_id !='' and del != 'Y' and id in (174, 207, 797) ";

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){

		$emp_id 			= $row['emp_id'];
		$onbehalf_emp_id 	= $row['onbehalf_emp_id'];
		$gtype				= $row['exp_type'];
		
		if($gtype =='C'){
			$doc_type = 'CE';
		}
		else if($gtype =='R'){
			$doc_type = 'RE';
		}
		else if($gtype =='T'){
			$doc_type = 'TE';
		}
		
		if($gtype =='C'){
			
			$sql = "select * from sma_party_mst where id = '$emp_id' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$emp_name = $r2['party_name'];	
		}
		else {
			
			$sql = "select * from sma_user where id = '$onbehalf_emp_id' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$emp_name = $r2['username'];
			
		}	
		
		$trans_type	 			= $row['trans_type'];
		
		$sql = "SELECT * FROM `sma_workflow_type` where id = '$trans_type' ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$workflow_type 		= $com['workflow_type'];
		
		$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$comp_name 		= $r1['comp_name'];
		$comp_code 		= $r1['comp_code'];
		
		$dated = date('d-m-Y', strtotime($row['dated']));
		if($dated=='01-01-1970'){ $dated='';}
		
		$approval_ref_no 	= $row['approval_ref_no'];
		$idd			 	= $row['id'];
		$advance_amount 	= $row['advance_amount'];
		//$utr_no				= $row['utr_no'];
		
		$sql = "SELECT * FROM `workflow_history` where doc_type = '$doc_type' and doc_id = '$idd' and status in ('Approved', 'Submitted') order by id desc ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$create_by 		= $com['create_by'];
		
		$sql = "SELECT * FROM `sma_user` where id = '$create_by' ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$approver_name 		= $com['username'];
		
		$hdr_msg = "<tr>
					<td>".$idd."</td>
					<td>".$emp_name."</td>
					<td>".$dated ."</td>
					<td>".$approval_ref_no ."</td>
					<td>".$comp_name."</td>";
		
		//$message .= $hdr_msg;
					
		$sql  = " SELECT * FROM `sma_expenses` where exp_type = '$gtype' and approval_ref_no = '$idd' ";
//echo $sql;	
		$res3 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$ln=0;
		$tot_amount = 0 ;
		while($r1 	= mysqli_fetch_array($res3)){
			
			$dated = date('d-m-Y', strtotime($r1['dated']));
			if($dated=='01-01-1970'){ $dated='';}
			$reference				= $r1['reference'];
			$reference_id			= $r1['reference'];
			$reference_invoice_no 	= $r1['invoice_no'];
			$budget_id 		= $r1['budget_id'];
			$amount 		= $r1['amount'];
			$gst_amount 	= $r1['gst_amount'];
			$note 			= $r1['note'];
			$gst_flag 		= $r1['gst_flag'];
			
			$sql="SELECT * from sma_product where id = '$reference_id' ";
			$q2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($q2);
			$reference = $r2['name'];
			
			$sql = " SELECT * FROM `sma_budget` where id = '$budget_id'  ";
			$q2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($q2);
			$budget_name  		= $r2['budget_name'];
			$budget_head	  	= $r2['budget_head'];
			$total_budget 		= $r2['total_budget'];
			
			$sql = "SELECT * FROM `sma_budget_name`  where id = '$budget_name' ";
			$comresult 	= mysqli_query($con,$sql);
			$com 		= mysqli_fetch_array($comresult);
			$budget_name 		= $com['name'];
			
			$sql = "SELECT * from sma_budget_subgroup where id = '$budget_head' ";
			$res = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($res);
			$budget_head 		= $r2['budget_head'];
			
			$tot_amount += $amount + $gst_amount; 

		}
		
			$message .= $hdr_msg."<td>".$reference_invoice_no ."</td>
					<td>".$reference."</td>
					<td>".$dated ."</td>
					<td>".$tot_amount ."</td>
					<td>".$note."</td>
					
					<td>".$budget_name."</td>
					<td>".$budget_head."</td>
					<td>".$total_budget."</td>
					<td>".$approver_name."</td>
					<td> ".$workflow_type." </td>
					</tr>";
			
			$tot_amount =0 ;
		
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
		
		$fl_name = 'regular_exp_export_data.xls';
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
			$fl_name = 'approval_memo.pdf';
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