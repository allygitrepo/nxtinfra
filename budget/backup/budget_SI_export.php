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
				<tr>
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
					
					<td style='width: 10%;'><b>Used Amount</b></td>
					<td style='width: 10%;'><b>Bal.Budget</b></td>
					
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 12pt;'>";
		
		$grand_total_amount = '';
		$budget_head_prev	= '';
		$project_prev = '';
		
		$_SESSION['project_a'] 		= $_POST['project'];
		$_SESSION['budget_name_a'] 	= $_POST['budget_name'];
		$_SESSION['budget_head_a'] 	= $_POST['budget_head'];
					
		$project_v 		= $_SESSION['project_a'];
		$budget_name_v 	= $_SESSION['budget_name_a'];
		$budget_head_v 	= $_SESSION['budget_head_a'];

/* echo $project_v. ' ' . $budget_name_v. ' ' .	$budget_head_v;
	exit();
	 */
		if($budget_name_v=='All'){
			$budget_name_v='';
		}
		if($budget_head_v=='All'){
			$budget_head_v ='';
		}
		
		$project_tmp = '';
		if($project_v=='All'){
			$project_v	= '';
			$project_tmp = 'All';
			$sql = "select * from company order by comp_id ";
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){
				$project_v .= $r2['comp_id'].',';
			}
			$project_v.= '0';
		}
		
	$sqlb = '';
	$sqlh = '';
	if(!empty($budget_head_v)){
		$sqlh .= " and c.id 	= '$budget_head_v' ";
		$sqlhd .= " and d.id 	= '$budget_head_v' ";
	}
	if(!empty($budget_name_v)){
		$sqlb .= " and c.id		= '$budget_name_v' ";
		$sqlbd .= " and d.id		= '$budget_name_v' ";
	}

	$sql = "SELECT distinct(a.si_hdr_id) as id, 'SI' as ttype, a.material_id, a.description, e.company_id, a.budget_id, a.amount, b.id as material_id, b.name as material_name, c.budget_head as budget_head, '' as invoice_no
				FROM sma_supplier_invoice_details a, `sma_product` b , `sma_budget` c, sma_supplier_invoice e
					WHERE si_srno > 0 and a.material_id =  b.id and e.status = 'Completed'
					and c.id		 	 	= a.budget_id
					and a.si_hdr_id 		= e.id
					and e.company_id 		in ( $project_v )
					and c.locked 			!='Y' 
					and e.del				!='Y' $sqlh 	$sqlb
			union					
			SELECT 	distinct(a.approval_ref_no) as id, 'CO' as ttype, b.id as material_id , a.note as description, c.company_id, '' as budget_id, a.amount, a.reference as material_id, b.account_name as material_name, d.budget_head as budget_head, a.invoice_no as invoice_no
			  from sma_expenses a, account_mst b, sma_travel_expenses c, sma_budget d
			 where b.id = a.reference and a.exp_type = 'C'  and c.status = 'Completed'
			   and a.approval_ref_no 	= c.id 
			   and d.id 				= a.budget_id
			   and c.company_id 		in ( $project_v )
			   and d.locked 			!='Y'  
			   and c.del				!='Y' $sqlhd 	$sqlbd
			union					
			SELECT 	distinct(a.approval_ref_no) as id, 'TE' as ttype, b.id as material_id , a.note as description, c.company_id, '' as budget_id, a.amount, a.reference as material_id, b.account_name as material_name, d.budget_head as budget_head, a.invoice_no as invoice_no
			  from sma_expenses a, account_mst b, sma_travel_expenses c, sma_budget d 
			 where b.id = a.reference and a.exp_type = 'T' and c.status = 'Completed'
			   and a.approval_ref_no 	= c.id 
			   and d.id 				= a.budget_id
			   and c.company_id 		in ( $project_v )
			   and d.locked 			!='Y'  
			   and c.del				!='Y' $sqlhd 	$sqlbd
			union					
			SELECT 	distinct(a.approval_ref_no) as id, 'RE' as ttype, b.id as material_id, a.note as description, c.company_id, '' as budget_id, a.amount, a.reference as material_id, b.account_name as material_name, d.budget_head as budget_head, a.invoice_no as invoice_no
			  from sma_expenses a, account_mst b, sma_travel_expenses c, sma_budget d 
			 where b.id = a.reference and a.exp_type = 'R' and c.status = 'Completed'
			   and a.approval_ref_no 	= c.id 
			   and d.id 				= a.budget_id
			   and c.company_id 		in ( $project_v )
			   and d.locked 			!='Y'  
			   and c.del				!='Y'  $sqlhd 	$sqlbd
			   ORDER BY budget_head , id ";

//and c.dated				>= '$date_from'
//and c.dated				<= '$date_to'
			    
//echo $sql."<BR>";
//exit();

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
			$ttype			= $row['ttype'];
			if($ttype=='SI'){
				$si_hdr_id 		= $row['id'];
				$sql 	= " SELECT * FROM `sma_supplier_invoice` where id = '$si_hdr_id' ";
				$q1 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($q1);
				$company_id   	= $r1['company_id'];
				$our_po_ref_no  = $r1['our_po_ref_no'];
				$suplier_name	= $r1['suplier_name'];
				$invoice_date 	= date('d-m-Y', strtotime($r1['invoice_date']));
			}
			else if($ttype=='CO' || $ttype=='TE' || $ttype=='RE' ){
				$si_hdr_id 		= $row['id'];
				$sql 	= " SELECT * FROM `sma_travel_expenses` where id = '$si_hdr_id' ";
				$q1 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($q1);
				$company_id   	= $r1['company_id'];
				//$our_po_ref_no  = $row['invoice_no'];
				$suplier_name	= $r1['emp_id'];
				$invoice_date 	= date('d-m-Y', strtotime($r1['dated']));
			}
			
			$amount				= $row['amount'];
			$budget_head		= $row['budget_head'];
			$budget_id			= $row['budget_id'];
			$material_name  	= $row['material_name'];
			//$company_id   	= $row['company_id'];
			
			$sql = "SELECT id, project, budget_name, budget_head, total_budget, blocked_budget, used_budget, locked FROM `sma_budget`
				where id = '$budget_id'
				ORDER BY `budget_name` ASC ";

			$q2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($q2);
			$total_budget   	= $r2['total_budget'];
			$budget_name 		= $r2['budget_name'];
			$budget_head	 	= $r2['budget_head'];
			
			$used_budget		= $amount;
			$balance_budget 	= $total_budget - $used_budget;
		
//echo $project_v. ' ' . $project_tmp . ' ' . $budget_head_v . ' ' . $budget_name_v;
//exit();

		/* 	if( $project_tmp != 'All' ){
				if(!empty($project_v) ){
					if( $project_v != $project ){
						continue;
					}
				}
			}
			
			if ( !empty($budget_head_v)  ){
				if( $budget_head_v != $budget_head ){
					continue;
				}
			}
			if ( !empty($budget_name_v)  ){
				if( $budget_name_v != $budget_name ){
					continue;
				}
			} */
			
			$sql = "SELECT * from company where comp_id = '$company_id' ";
			$res = mysqli_query($con, $sql);
			//echo mysqli_error($con);
			$r3 = mysqli_fetch_array($res);
			$comp_name = $r3['comp_name'];
			
			$project_prev = $company_id;
			
			$sql = "SELECT * from sma_budget_name where id = '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($res);
			
			$bname = $r2['name'];
			
			if($ttype=='SI'){
				$sql  = " SELECT * FROM `sma_purchase_order` where id = '$our_po_ref_no' ";
				$q6 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r6 = mysqli_fetch_array($q6);
				$po_number   	= $r6['po_number'];
			}
			else {
				$po_number   	= $row['invoice_no'];
			}
			
			$sql  = " SELECT * FROM `sma_party_mst` where id = '$suplier_name' ";
			$q7 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r7 = mysqli_fetch_array($q7);
			$party_name   	= $r7['party_name'];
			
			if($ttype=='CO'){
				$doc_type		= 'Company';
			}
			else if($ttype=='SI'){
				$doc_type		= 'Supplier';
			}
			else if($ttype=='TE'){
				$doc_type		= 'Travel';
				
				$sql  = " SELECT * FROM `sma_user` where id = '$suplier_name' ";
				$q7 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r7 = mysqli_fetch_array($q7);
				$party_name   	= $r7['username'];
				
			}
			else if($ttype=='RE'){
				$doc_type		= 'Regular';
				$sql  = " SELECT * FROM `sma_user` where id = '$suplier_name' ";
				$q7 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r7 = mysqli_fetch_array($q7);
				$party_name   	= $r7['username'];
			}
			
			//$doc_type		= $ttype;
			
	//echo $budget_head_prev . ' != ' . $budget_head ;		
			
			if( $budget_head_prev != $budget_head ){
				$message .= "<tr>
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
						<td>".$comp_name."</td>
						<td>".$bname."</td>
						<td>".$budget_head."</td>
						<td>".$party_name."</td>
						<td>".$doc_type."</td>
						<td>".$po_number."</td>
						<td>".$si_hdr_id."</td>
						<td>".$invoice_date ."</td>
						<td>".$product_category."</td>
						<td>".$material_name."</td>
			
						<td style='width: 10%;text-align: right;'>".bcadd($used_budget, 0,2)."</td>
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

		