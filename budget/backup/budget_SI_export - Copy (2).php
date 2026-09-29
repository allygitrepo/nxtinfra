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
			<tr><th style='width: 100%;' colspan='11'> Used Budget Transaction List </th></tr></table>";		
	
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 10%;'><b>Year</b></td>
					<td style='width: 40%;'><b>Company</b></td>
					<td style='width: 10%;'><b>Budget Name</b> </td>
					<td style='width: 15%;'><b>Budget Head</b></td>
					<td style='width: 15%;'><b>Vendor Name</b></td>

					<td style='width: 15%;'><b>Opening Balance/<BR>Doc Type</b></td>
					
					<td style='width: 10%;'><b>PO.No.</b></td>
					<td style='width: 10%;'><b>SI.No.</b></td>
					<td style='width: 10%;'><b>Date</b></td>
					
					<td style='width: 15%;'><b>Category</b></td>
					<td style='width: 15%;'><b>Material Name</b></td>
					
					<td style='width: 10%;'><b>Amount</b></td>
					<td style='width: 10%;'><b>Bal.Budget</b></td>
					
					
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 12pt;'>";
		
		$grand_total_amount = '';
		$budget_head_prev	= '';
		$project_prev = '';
		
	//$tableName	= "sma_budget";
		$project_v = $_SESSION['project_a'];
		$budget_name_v = $_SESSION['budget_name_a'];
		$budget_head_v = $_SESSION['budget_head_a'];
		$account_year_v = $_SESSION['account_year_a'];

	//$sql = $_SESSION['sql'];
					
//	$sql = "SELECT a.si_hdr_id, a.material_id, a.description, a.account_year, a.company_id, a.budget_id, a.amount , b.id as material_id , 
//			b.name as material_name, b.`group` as material_group
//				FROM sma_supplier_invoice_details a, `sma_product` b 
//					WHERE si_srno > 0 and a.material_id =  b.id
//					and a.company_id = '$project_v' 
//						ORDER BY material_name  ASC ";
						
	$sql = "SELECT distinct(a.si_hdr_id), a.material_id, a.description, a.account_year, e.company_id, a.budget_id, a.amount , b.id as material_id , 
			b.name as material_name, b.`group` as material_group, c.budget_category
				FROM sma_supplier_invoice_details a, `sma_product` b , `sma_budget` c, sma_product_group d, sma_supplier_invoice e
					WHERE si_srno > 0 and a.material_id =  b.id
					and b.group 			= d.id
					and c.budget_category 	= d.budget_head
					and a.si_hdr_id = e.id
					and e.company_id = '$project_v' 
					ORDER BY c.budget_category , a.si_hdr_id ASC ";
					
//echo $sql."<BR>";
//exit();

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
			$si_hdr_id 		= $row['si_hdr_id'];
			$sql 	= " SELECT * FROM `sma_supplier_invoice` where id = '$si_hdr_id' ";
			$q1 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r1 = mysqli_fetch_array($q1);
			$company_id   	= $r1['company_id'];
			$our_po_ref_no  = $r1['our_po_ref_no'];
			$suplier_name	= $r1['suplier_name'];
			$invoice_date 	= date('d-m-Y', strtotime($r1['invoice_date']));
			
			$amount			= $row['amount'];
			$material_group = $row['material_group'];
			$material_name  = $row['material_name'];
			//$company_id   	= $row['company_id'];
			$account_year	= $row['account_year'];
			
			$account_year	= '3'; //'2019-20'
			
			$sql = "SELECT 	distinct(a.id), a.project, a.budget_name, a.budget_category, a.account_year, a.total_budget, a.blocked_budget, a.used_budget, a.locked, b.budget_name, b.budget_head , b.description
			FROM `sma_budget` a, sma_product_group b
			where a.locked !='Y' and a.budget_name = b.budget_name  
				and a.budget_category 	= b.budget_head
				and a.budget_category 	= '$material_group'
				and a.account_year	 	= '$account_year'
				and a.project 			= '$company_id'
			ORDER BY `a`.`budget_name` ASC ";
	//echo $sql."<BR>";
	//exit();		
			$q2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($q2);
			$total_budget   	= $r2['total_budget'];
			$used_budget		= $amount;
			$balance_budget 	= $total_budget - $used_budget;
		
			$project 			= $r2['project'];
			$budget_name 		= $r2['budget_name'];
			$budget_head	 	= $r2['budget_category'];
			$account_year	 	= $r2['account_year'];
			$product_category	= $r2['description'];

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
			
		//	if (!empty($account_year_v)){
		//		if($account_year_v != $account_year){
		//			continue;
		//		}
		//	}
			
			$sql = "SELECT * from company where comp_id = '$company_id' ";
			$res = mysqli_query($con, $sql);
			//echo mysqli_error($con);
			$r3 = mysqli_fetch_array($res);
			$comp_name = $r3['comp_name'];
			
			
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
			else { $acyr = $account_year; }
			
			$project_prev = $company_id;
			
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
			
			$sql  = " SELECT * FROM `sma_purchase_order` where id = '$our_po_ref_no' ";
//echo $sql;
//exit();
			
			$q6 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r6 = mysqli_fetch_array($q6);
			$po_number   	= $r6['po_number'];
			
			
			$sql  = " SELECT * FROM `sma_party_mst` where id = '$suplier_name' ";
			$q7 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r7 = mysqli_fetch_array($q7);
			$party_name   	= $r7['party_name'];
			
			$doc_type		= 'SI';
			
	//echo $budget_head_prev . ' != ' . $budget_head ;		
			
			if($budget_head_prev != $budget_head ){
				$message .= "<tr>
						<td>".$acyr."</td>
						<td>".$comp_name."</td>
						<td><b>".$bname."</b></td>
						<td><b>".$category."</b></td>
						<td></td>
						<td style='width: 10%;text-align: right;'><b>".($total_budget)."</b></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						
					</tr>";

			//echo $message;
			//exit();
			
					$bal_budget = $total_budget;
			}
			
			$budget_head_prev = $budget_head ;
			$category_prev    = $category;
			
			$bal_budget = $bal_budget - $used_budget;
			
			$message .= "<tr>
						<td>".$acyr."</td>
						<td>".$comp_name."</td>
						<td>".$bname."</td>
						<td>".$category."</td>
						<td>".$party_name."</td>
						<td>".$doc_type."</td>
						<td>".$po_number."</td>
						<td>".$si_hdr_id."</td>
						<td>".$invoice_date ."</td>
						<td>".$product_category."</td>
						<td>".$material_name."</td>
			
						<td style='width: 10%;text-align: right;'>".bcadd($used_budget * -1, 0,2)."</td>
						<td style='width: 10%;text-align: right;'>".bcadd($bal_budget, 0,2)."</td>";
					$message .= "</tr>";
			
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
		$fl_name = 'budget_si_trans_export.xls';
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

		