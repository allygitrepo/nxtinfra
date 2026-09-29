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
//	$department = $_POST['department'];	
//	$supplier_id= $_POST['supplier_id'];	
//	$company_id= $_POST['company_id'];
		
	$message ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='16'> Budget Transaction List </th></tr></table>";		
	
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 10%;'><b>Year</b></td>
					<td style='width: 40%;'><b>Company</b></td>
					<td style='width: 10%;'><b>Budget Name</b> </td>
					<td style='width: 15%;'><b>Budget Head</b></td>

					<td style='width: 15%;'><b>Opening Balance</b></td>
					
					<td style='width: 10%;'><b>Doc Type</b></td>
					<td style='width: 10%;'><b>Doc No.</b></td>
					<td style='width: 10%;'><b>Date</b></td>
					<td style='width: 15%;'><b>Po Number</b></td>
					
					<td style='width: 15%;'><b>Vendor </b></td>
					<td style='width: 15%;'><b>Category</b></td>
					<td style='width: 15%;'><b>Material Name</b></td>
					
					<td style='width: 10%;'><b>Qty.</b></td>
					<td style='width: 10%;'><b>Rate</b></td>
					<td style='width: 10%;'><b>GST</b></td>
					<td style='width: 10%;'><b>Amount</b></td>
					
					
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 12pt;'>";
				
		$id				= $_GET['id'];
	
		$grand_total_amount = '';
		$budget_head_prev	= '';
		$project_prev = '';
		
	//$tableName	= "sma_budget";
		$project_v = $_SESSION['project_a'];
		$budget_name_v = $_SESSION['budget_name_a'];
		$budget_head_v = $_SESSION['budget_head_a'];
		$account_year_v = $_SESSION['account_year_a'];

	$sql = $_SESSION['sql'];
	$sql; 		//= " SELECT * FROM $tableName order by account_year ";
	
	$sql = "SELECT a.po_number, a.approval_memo_ref, a.id as po_id, a.budget_head as bh, c.budget_head as budget_head, a.dated, a.budget_name, a.to_supplier
				FROM sma_purchase_order a, `sma_approval_memo` c 
					where a.approval_memo_ref = c.id and a.project = '$project_v' order by c.budget_head, a.po_number ";
//echo $sql."<BR>";


	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$dated 			= date('d-m-Y', strtotime($row['dated']));
		$po_number 		= $row['po_number'];
		$po_id		 	= $row['po_id'];
		$budget_head 	= $row['budget_head'];
		$to_supplier 	= $row['to_supplier'];
		$process_flag 	= '';

		$sql="SELECT * FROM sma_party_mst where id = '$to_supplier' ";
		$q2 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$vendor_name = $r2->party_name;

		$sql = " SELECT * FROM `sma_po_items` where purchase_id = '$po_id' ";	
//echo $sql." ###1 <BR>";		
		$respo = mysqli_query($con,$sql);
		while($pod = mysqli_fetch_array($respo)){
//		echo $sql."  ###2<BR>";
//		exit();	
			
			//$product_name 	= $pod['product_name'];
			$product_id		= $pod['product_id'];
			$quantity		= $pod['quantity'];
			$gst		 	= $pod['gst'];
			$unit_rate		= $pod['unit_rate'];
			$amount 	    = $quantity * $unit_rate;
		
			//$budget_head = $pod['budget_head'];
			if($budget_head==0){continue;}

			$sql = "SELECT a.name , b.description FROM `sma_product` a, `sma_product_group` b where a.group  = b.id and a.id = '$product_id' ";
			$q2 = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			$product_name 		= $r2->name;
			$product_category 	= $r2->description;

			
			if($budget_head_prev != $budget_head ){

				if (!empty($account_year_v)){
					$sql = "SELECT * from sma_budget where id = '$budget_head' and account_year = '$account_year_v' ";
				}
				else {
					$sql = "SELECT * from sma_budget where id = '$budget_head' ";
				}
				
				$res = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($res);
				$project 			= $r2['project'];
				$budget_name 		= $r2['budget_name'];
				$budget_head	 	= $r2['budget_category'];
				$account_year	 	= $r2['account_year'];
				$total_budget 		= $r2['total_budget'];
				$used_budget 		= $r2['used_budget'];
			
			}
			
			if(!empty($project_v)){
				if($project_v != $project){
					continue;
				}
			}
			if (!empty($budget_head_v)){
				if($budget_head_v !=$budget_head){
					continue;
				}
			}
			if (!empty($budget_name_v)){
				if($budget_name_v !=$budget_name){
					continue;
				}
			}

			$balance_budget = $total_budget - $used_budget;
			
			$sql = "SELECT * from company where comp_id = '$project' ";
			$com = mysqli_query($con, $sql);
			//echo mysqli_error($con);
			$r2 = mysqli_fetch_array($com);
			$comp_name = $r2['comp_name'];
			
			
			if ($account_year=='1'){
				$acyr = '2017-2018';
			}
			else if ($account_year=='2'){
				$acyr = '2018-2019';
			}
			else if ($account_year=='3'){
				$acyr = '2019-2020';
			}
			else if ($account_year=='4'){
				$acyr = '2020-2021';
			}
			else if ($account_year=='5'){
				$acyr = '2021-2022';
			}
			
			
			$project_prev = $project;
			
			$budget_category = $budget_head;
			$sql = "SELECT * from sma_budget_category where id = '$budget_category' ";
			$res = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($res);
			
			$category = $r2['category'];
			
			$budget_name = $budget_name;
			$sql = "SELECT * from sma_budget_name where id = '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($res);
			
			$bname = $r2['name'];
			
			$doc_type		= 'PO';
			
			$total_amount 	= round($amount + ($amount * $gst / 100),2);
			$total_amount 	= bcadd($total_amount, 0, 2);
			
			$po_id_prev		 	= $po_id;	
			
			if($budget_head_prev != $budget_head ){
				$message .= "<tr>
						<td>".$acyr."</td>
						<td>".$comp_name."</td>
						<td><b>".$bname."</b></td>
						<td><b>".$category."</b></td>
						<td style='width: 10%;text-align: right;'><b>".($total_budget)."</b></td>
						<td>".$doc_type."</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>";

			}
			
			$budget_head_prev = $budget_head ;
			$category_prev    = $category;
			
			
			$message .= "<tr>
						<td>".$acyr."</td>
						<td>".$comp_name."</td>
						<td>".$bname."</td>
						<td>".$category."</td>
						<td></td>
						<td>".$doc_type."</td>
						<td>".$po_id."</td>
						<td>".$dated ."</td>
						<td style='text-align: left;'>".$po_number."</td>
						<td>".$vendor_name."</td>
						<td>".$product_category."</td>
						<td>".$product_name."</td>
			
						<td>".$quantity ."</td>
						<td>".$unit_rate ."</td>
						<td>".$gst ."</td>
						<td style='width: 10%;text-align: right;'>".bcadd($total_amount * -1, 0,2)."</td>";
					$message .= "</tr>";
			
		}
			
	}
	
/*	$message .= "<tr>
					<td></td>
					<td></td>
					<td>".$bname."</td>
					<td>".$category."</td>
					<td></td>
					<td></td>
					<td></td>
					<td><b>Total Amount</b></td>
					<td></td><td></td><td></td><td></td>
					<td style='width: 10%;text-align: right;'>".($grand_total_amount * -1)."</td>
					<td colspan='01'></td>
				</tr>";

*/
	
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
		$fl_name = 'budget_trans_export.xls';
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

		