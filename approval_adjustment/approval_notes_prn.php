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

	$message1 .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; font-size: 10pt;margin-left:10px;margin-top: 10px;'>
			<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; font-size: 16pt;margin-left:10px;'><tr><td style='width: 95%;'> " . $comp_name."</td></tr></table>";
//	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; margin-left:10px;font-size: 10pt;'><tr><td style='width: 95%;'>Location : ". $loc_name."</td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center;margin-left:10px; font-size: 12px;'><tr><td style='width: 95%;'>Office Address : ". $comp_addr1.', '.$comp_addr2.', '.$comp_addr3.' '.$comp_city.' Pincode : '.$comp_pincode."</td></tr></table>";

	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; margin-left:10px;font-size: 20px;'>
			<tr><td style='width: 95%;'> Approval Memo</td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 100%; text-align: center; margin-left:20px;font-size: 05pt;'>
			<tr><td style='width: 90%;'> <hr style='height: 1px;'> </td></tr></table>";
//$head = $message;

//$message ='';
/* 	
$message .= '<page backtop=26mm" backbottom="14mm" backleft="10mm" backright="2mm" pagegroup="new">
    <page_header>
        <table class="page_header" style="width: 103%; text-align: center;font-size: 18pt">
            <tr>
                <td style="width: 103%; text-align: center123;text-align: center;">
                    '.$head.'
                </td>
            </tr>
        </table>
    </page_header>';
	
$message123 .= '<page_footer>
        <table class="page_footer" >
            <tr>
                <td style="width: 100%; text-align: right">
                    page [[page_cu]]/[[page_nb]]
                </td>
            </tr>
        </table>
    </page_footer>
</page>';
 */
	$id				= $_GET['id'];
	$tableName		= "sma_approval_memo";
	$sql 	= "SELECT * FROM $tableName where id = '$id'";

//	$sql = $_SESSION['sqlex'];

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$approval_no			= $row['id'];
		$dated  				= date('d-m-Y', strtotime($row['dated']));
		$account_year			= $row['account_year'];
		$company				= $row['company'];
		$project 				= $row['project'];
		$department  			= $row['department'];
		$budget_head	 		= $row['budget_head'];
		$subject 				= $row['subject'];
		$background 			= $row['background'];
		$scope_of_work 			= $row['scope_of_work'];
		$deviations_from_sop 	= $row['deviations_from_sop'];
		$important_terms_conditions	= $row['important_terms_conditions'];
		$additional_costs		= $row['additional_costs'];
		$against_indent_no		= $row['against_indent_no'];
		$overhead_exp			= $row['overhead_exp'];
//echo $row['draft_dated'];
		
		$maker					= $row['draft_by'];
		$maker_date				= date('d-m-Y  h:i:s', strtotime($row['draft_dated']));
//		$checker				= $row['changed_by'];
		
		$sql 	= "SELECT * FROM sma_department where id = '$department'";
		$result = mysqli_query($con,$sql);
		$dep 	= mysqli_fetch_array($result);
		$department  	 = $dep['name'];
		
	}
	
		$company_id		= $company;
		$sql 	= "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		$budget_control_gst = $r2['budget_control_gst'];
		
		
			
	$message .= "<table cellspacing='0' style='width: 95%; text-align: left;margin-left:0px; font-size: 13px;'>
			<tr><th style='width: 40%;'> Approval No.: $approval_no </th><th style='width: 35%;'> Department : $department </th><th style='width: 25%;text-align: right'>Dated : $dated </th></tr></table>";

	$message .= "<table cellspacing='0' style='width: 95%; text-align: left;margin-left:0px; font-size: 13px;'>
			<tr><th style='width: 100%;'> Subject : $subject</th></tr></table>";
			
	if(!empty($background)){
		$message .=  "<h4> Background</h4>";
	//	$message .= "<div style='border:1px solid black;'>".$background." </div>";
		$message .= "<table cellspacing='0' style='width: 95%; text-align: left; margin-left:0px;font-size: 12pt;' border='1'>
			<tr><td style='width: 100%;'>".$background." </td></tr></table>";
		
	}
	
	//$message .=  $background ;
	if(!empty($scope_of_work)){
		$message .=  "<h4> Scope of Work</h4>";
		//$message .=  $scope_of_work;
		$message .= "<table cellspacing='0' style='width: 95%; text-align: left; margin-left:0px;font-size: 12pt;' border='1'>
			<tr><td style='width: 100%;'>".$scope_of_work." </td></tr></table>";
	}
	if(!empty($deviations_from_sop)){
		$message .=  "<h4> Deviations from SOP</h4>";
		//$message .= "<div style='border:1px solid black;'>".$deviations_from_sop ." </div>";
		$message .= "<table cellspacing='0' style='width: 95%; text-align: left; margin-left:0px;font-size: 12pt;' border='1'>
			<tr><td style='width: 100%;'>".$deviations_from_sop." </td></tr></table>";
	}
	if(!empty($important_terms_conditions)){
		$message .=  "<h4> Term & Conditions</h4>";
		//$message .= "<div style='border:1px solid black;'>".$important_terms_conditions ." </div>";
		$message .= "<table cellspacing='0' style='width: 95%; text-align: left; margin-left:0px;font-size: 12pt;' border='1'>
			<tr><td style='width: 100%;'>".$important_terms_conditions." </td></tr></table>";
	}

	$message .= "<BR>";
		
	$message .= "<table cellspacing='0' style='width: 95%; text-align: left; margin-left:10px;font-size: 12pt;'>
			<tr><th style='width: 95%;'>Supplier Quotations Details</th></tr></table> ";

	$message .= "<table cellspacing='0' style='width: 95%; border: solid 1px black; background: #E7E7E7; margin-left:0px; font-size: 13px;' >
			<tr><td style='width: 10%;text-align: Center;font-size:12px;'> SrNo.</td>
				<td style='width: 30%;text-align: left;'>Supplier Name </td>
				<td style='width: 25%;'> Quote Ref.No. </td>
				<td style='width: 5%;text-align: left;'> KYC Status </td>
				<td style='width: 15%;text-align: right;'> Quoted Amount &nbsp;</td>
				<td style='width: 15%;text-align: left;'> Status </td>
			</tr></table>";
			
	$message .= '<table cellspacing="0" style="width: 95%; margin-left:0px; border: solid 1px #000000; ">';
	
	$total_selected_value = 0;
	$id		= $_GET['id'];
	$sql 	= "SELECT * FROM sma_approval_details where approval_hdr_id = '$id'";
//echo $sql;	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){

		$supplier_name		= $row['supplier_name'];
		$quote_ref_no		= $row['quote_ref_no'];
		$vendor_selected	= $row['vendor_selected'];
		$values   			= $row['values'];
		$remarks			= $row['remarks'];

		$sql 	= "SELECT * FROM sma_party_mst where id = '$supplier_name'";
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$s1 	= mysqli_fetch_array($res);
		$supplier_name  	 = $s1['party_name'];
		$supplier_kyc  	 	= $s1['party_kyc'];
		
		$fontw='';
		if($vendor_selected == 'Y'){
			$vendor_selected = 'Selected';
			$fontw='font-weight: bold';
			$total_selected_value = $total_selected_value + $values;
		}
		else {$vendor_selected='';}
		
	    ++$i;
	$message .= "<tr>
				<td style='width: 10%;text-align: Center;$fontw'> ".$i." </td>
				<td style='width: 30%;text-align: left;$fontw'>". $supplier_name . " </td>
				<td style='width: 25%;text-align: left;font-size: 12px;$fontw'> " . $quote_ref_no . " </td>
				<td style='width: 5%;text-align: left;$fontw'> " . $supplier_kyc . " </td>
				<td style='width: 15%;text-align: right;$fontw'> ".$values."  &nbsp;</td>
				<td style='width: 15%;text-align: left;$fontw'> " . $vendor_selected . " </td>
				
			</tr>";
	}
	
	$message .= "</table>";
	
	$message_a = '';
	//if($overhead_exp!='Y'){
		$message .=  "<h4> Product </h4>";
		
			
			$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left;  font-size: 13px;' border='.5'  >
					<tr>
						<th style='width: 15%;background: #E7E7E7;'>Product </th>
						<th style='width: 15%;background: #E7E7E7;'>Product Desc. </th>
						<th style='width: 10%;background: #E7E7E7;'>Cost Center Code</th>
						<th style='width: 12%;background: #E7E7E7;text-align:right;'> Quantity</th>
						<th style='width: 12%;background: #E7E7E7;text-align:right;'> Rate</th>
						<th style='width: 12%;background: #E7E7E7;text-align:right;'> Sub Total in Rs.</th>
						<th style='width: 10%;background: #E7E7E7;text-align:right;'> GST%</th>
						<th style='width: 12%;background: #E7E7E7;text-align:right;'> GST </th>
						<th style='width: 12%;background: #E7E7E7;text-align:right;'> Total in Rs.</th>
					</tr>";
					
					//</table>";
			
			//$message .= "<table cellspacing='0' style='width: 95%; text-align: left; font-size: 13px;' border='.5'  > ";
			
			$sql 	= "select * from sma_approval_items where approval_hdr_id = '$id' order by  budget_id, balance_budget desc, id ";			
			$qry 	= mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			while ($r2 	= mysqli_fetch_array($qry)){
		
				$budget_id  	 = $r2['budget_id'];
				$product_id  	 = $r2['product_id'];
				$product_name  	 = $r2['product_name'];
				$product_desc  	 = $r2['product_desc'];
				$quantity	  	 = $r2['quantity'];
				$unit_rate	  	 = $r2['unit_rate'];
				$gst		  	 = $r2['gst'];
				$balance_budget	 = $r2['balance_budget'];
				
				$sub_total_value = round(($quantity * $unit_rate),2);
				
				$gst_value 		 = round(( (($quantity * $unit_rate) * $gst) / 100 ),2);
				
				$total_value 	 = round(($quantity * $unit_rate) + ( (($quantity * $unit_rate) * $gst) / 100 ),2);
				
				$total_value_total = $total_value_total + $total_value;
				$gst_value_total   = $gst_value_total + $gst_value;
				$sub_total_value_total   = $sub_total_value_total + $sub_total_value;
				
				//$current_balance	= $balance_budget - $total_value;
				
				$sql 	= "SELECT b.name as budget_name, a.budget_code, a.budget_head as budget_head, a.blocked_budget, a.used_budget, a.total_budget , a.adjustment_budget
					FROM sma_budget a, sma_budget_name b
						where a.id = '$budget_id' and a.budget_name = b.id ";
				$res 	= mysqli_query($con,$sql);
				$error  = mysqli_error($con);
				$s1 	= mysqli_fetch_array($res);
				$budget_name  	 = $s1['budget_name'];
				$budget_head  	 = $s1['budget_head'];
				$budget_code	 = $s1['budget_code'];
				$total_budget    = $s1['total_budget'];
				$blocked_budget	 = $s1['blocked_budget'];
				$adjustment_budget= $s1['adjustment_budget'];
				$used_budget	 = $s1['used_budget'];
				
				$message .= "<tr>
							<td style='width: 15%;'>". $product_name . " </td>
							<td style='width: 15%;'>". $product_desc . " </td>
							<td style='width: 10%;'>". $budget_code ." </td>
							<td style='width: 12%;text-align:right;'>". $quantity . " </td>
							<td style='width: 12%;text-align:right;'>". $unit_rate . " </td>
							<td style='width: 12%;text-align:right;'>".  bcadd($sub_total_value,0,2) . " </td>
							<td style='width: 10%;text-align:right;'>". $gst . " </td>
							
							<td style='width: 12%;text-align:right;'>". $gst_value . " </td>
							<td style='width: 12%;text-align:right;'>".  bcadd($total_value,0,2) . " </td>
						</tr>
						";
						
						//$balance_budget.' <> '.
		}
		
		$message .= "<tr>
							<td style='width: 15%;'></td>
							<td style='width: 15%;'> </td>
							<td style='width: 10%;'> </td>
							<td style='width: 12%;text-align:right;'> </td>
							<td style='width: 12%;text-align:right;'></td>
							<th style='width: 12%;text-align:right;'>".  bcadd($sub_total_value_total,0,2) . " </th>
							<td style='width: 10%;text-align:right;'> </td>
							
							<th style='width: 12%;text-align:right;'>". $gst_value_total . " </th>
							<th style='width: 12%;text-align:right;'>".  bcadd($total_value_total,0,2) . " </th>
						</tr>
						";
						
		if($budget_control_gst=='N'){
			$message .= "<tr>
							<td style='width: 20%;'> &nbsp;</td>
							<td style='width: 20%;'> &nbsp;</td>
							<th style='width: 80%; text-align:right;' colspan='7' > * Budget Consideration : Without GST </th>
						</tr> ";
			$total_value_total = $sub_total_value_total;			
			
		}
		
		$message .= "</table>";
		
		
		$message.=  "<h4> Budget </h4>";
		$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left;  font-size: 13px;' border='.5'  >
					<tr>
						<th style='width: 15%;background: #E7E7E7;'>Cost Center Group </th>
						<th style='width: 15%;background: #E7E7E7;'>Cost Center Sub Group</th>
						<th style='width: 10%;background: #E7E7E7;'>Cost Center Code</th>
						<th style='width: 15%;background: #E7E7E7;text-align:right;'> Year Op.Budget Rs. </th>
						<th style='width: 15%;background: #E7E7E7;text-align:right;'> Balance Before this AP Memo Rs. </th>
						<th style='width: 15%;background: #E7E7E7;text-align:right;'> This Approval Amount Rs. </th>
						<th style='width: 15%;background: #E7E7E7;text-align:right;'> Balance After This AP Memo Rs. </th>
						
					</tr>";
		//			</table>";
		//$message .= '<table cellspacing="0" style="width: 95%; margin-left:0px; border: solid .5px #000000; ">';
			
			$budget_code_prev = '';
			$current_balance	= 0;
			$budget_total_value = 0;
			
			$sql 	= "select budget_id from sma_approval_items where approval_hdr_id = '$id' group by budget_id ";	
		//echo			$sql." ###1<BR>";
			$qry 	= mysqli_query($con,$sql);
			$error  = mysqli_error($con);
		while ($r2 	= mysqli_fetch_array($qry)){
		
				$budget_id  	 = $r2['budget_id'];

				$sql 	= "SELECT b.name as budget_name, a.budget_code, a.budget_head as budget_head, a.blocked_budget, a.used_budget, a.total_budget , a.adjustment_budget
					FROM sma_budget a, sma_budget_name b
						where a.id = '$budget_id' and a.budget_name = b.id ";
//echo			$sql." ###2<BR>";						
				$res 	= mysqli_query($con,$sql);
				$error  = mysqli_error($con);
				$s1 	= mysqli_fetch_array($res);
				$budget_name  	 = $s1['budget_name'];
				$budget_head  	 = $s1['budget_head'];
				$budget_code	 = $s1['budget_code'];
				$total_budget    = $s1['total_budget'];
				$blocked_budget	 = $s1['blocked_budget'];
				$adjustment_budget= $s1['adjustment_budget'];
				$used_budget	 = $s1['used_budget'];
				$total_budget    = $total_budget + $adjustment_budget;
				
				$sql 	= "SELECT * FROM sma_budget_subgroup where id = '$budget_head' ";
				$res 	= mysqli_query($con,$sql);
				$error  = mysqli_error($con);
				$s2 	= mysqli_fetch_array($res);
				$budget_head  	 = $s2['budget_head'];
				
				$sql = "select budget_id, max(balance_budget) as max_balance_budget from sma_approval_items where approval_hdr_id = '$id' and budget_id = '$budget_id' group by budget_id";
//echo			$sql." ###3<BR>";		
					$res1 	= mysqli_query($con,$sql);
					$error  = mysqli_error($con);
					$s11 	= mysqli_fetch_array($res1);
					$max_balance_budget = $s11['max_balance_budget'] + $adjustment_budget;
					$current_balance = $max_balance_budget;
				
				if($budget_control_gst=='Y'){
				$sql = "select budget_id, max(balance_budget) as balance_budget, sum(round((quantity * unit_rate) + ( ((quantity * unit_rate) * gst) / 100 ),2)) as total_value from sma_approval_items where approval_hdr_id = '$id' and budget_id = '$budget_id' order by balance_budget  "; //limit 0, 1
				}
				else if($budget_control_gst=='N'){
					$sql = "select budget_id, max(balance_budget) as balance_budget, sum(round((quantity * unit_rate),2)) as total_value from sma_approval_items where approval_hdr_id = '$id' and budget_id = '$budget_id' order by balance_budget  "; //limit 0, 1
				}
//echo			$sql." ###4<BR>";					
				$res1 	= mysqli_query($con,$sql);
				$error  = mysqli_error($con);
				$s11 	= mysqli_fetch_array($res1);
				$min_balance_budget = $s11['balance_budget'];
				$total_value 		= $s11['total_value'] ;
				$current_balance = $min_balance_budget - $total_value + $adjustment_budget;
				
					$message .= "<tr>
							<td style='width: 15%;border-bottom: solid 0.5px #000;'>". $budget_name . " </td>
							<td style='width: 15%;border-bottom: solid 0.5px #000;'>". $budget_head . " </td>
							<td style='width: 15%;border-bottom: solid 0.5px #000;'>". $budget_code. " </td>
							<td style='width: 15%;text-align:right;border-bottom: solid 0.5px #000;'>". $total_budget . " </td>
							<td style='width: 15%;text-align:right;border-bottom: solid 0.5px #000;'>". bcadd($max_balance_budget,0,2) . "
							</td>
							<td style='width: 15%;text-align:right;border-bottom: solid 0.5px #000;'>". bcadd($total_value,0,2) . " </td>
							<td style='width: 15%;border-bottom: solid 0.5px #000;text-align:right;'>". bcadd($current_balance,0,2) . " </td>
						</tr> ";
					//<td style='width: 15%;text-align:right;border-bottom: solid 0.5px #000;'>". bcadd($total_value_total,0,2) . " </td>
					$current_balance	= 0;
					
					
				}
				
			
		$message .= "</table>";
		
	
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
		$sql 	= "SELECT a.create_by, a.create_date, a.status, b.username FROM `workflow_history` a, `sma_user` b where a.doc_type = 'AP' and a.doc_id = '$id' and b.id = a.create_by order by a.id ";
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
//	}
	
	
	$message .=  "<h4> Documents</h4>";
	
	$modulePath = "approval/";
	$baseurl2  = $baseurl.$modulePath;
	
	$id		= $_GET['id'];
	$sql 	= "SELECT * FROM file_uploads where module = 'AP' and reference_id = '$id'";
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$file_name = $row['file_name'];
		$file_path = $row['file_path'];
		$doc_type = $row['doc_type'];
		$share_point_link = $row['share_point_link'];
		
		//$baseurl1 = $baseurl2.$file_path.'/'.$file_name;
		$baseurl1 = $share_point_link;
		$message .= "<table cellspacing='2' style='width: 95%; border: solid 1px black; margin-left:0px; font-size: 12px;' >
			<tr><td style='width: 100%;text-align: left;font-size:12px;'><a href='$baseurl1'>$baseurl1</a></td></tr></table>";
	
	}
	
	//$message .= "<table cellspacing='0' style='width: 95%; text-align: center; font-size: 01pt;'><tr><td style='width: 95%;'> <hr style='height: .5px;'> </td></tr></table>";
	
	$ln  = 2;
	$l   =  $i;
	
	for($l = $l; $l < $ln; $l++){
		$message .= "<table border='0' cellspacing='-1' style='width: 95%; text-align: center;margin-left:0px; font-size: 10pt;' border='0'>
					<tr><td style='width: 6%;text-align: Center;'> &nbsp;</td>
				<td style='width: 24%;text-align: left;'>&nbsp; </td>
				<td style='width: 30%;text-align: center;'> &nbsp; </td>
				<td style='width: 6%;text-align: center;'> &nbsp; </td>
				<td style='width: 8%;text-align: right;'> &nbsp; </td>
				<td style='width: 8%;text-align: right;'> &nbsp; </td>
				<td style='width: 8%;text-align: center;'> &nbsp; </td>
				<td style='width: 10%;text-align: right;'> &nbsp;</td>
			</tr></table>";
	}
	
	for($l = 0; $l < 1; $l++){
	
		$message .= "<table cellspacing='0' ><tr><td> &nbsp;</td></tr></table>";
	
	}
		
	$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; margin-left:10px;font-size: 10pt;'>
			<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";		
	
//	$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; font-size: 10pt;'>
//			<tr><td style='width: 95%;text-align: Center;'>This is a computer-generated document. No signature is required &nbsp; </td></tr></table>";		

//	$message .= "</div>";
	
	
print $message;
exit();
	
    // get the HTML
    ob_start();
	
    // convert to PDF
	if($prn=='pdf'){
	//$baseurl
		//$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once('../html2pdf/html2pdf.class.php');
		try
		{
			$fl_name = 'approval_notes_'.$id. '.pdf';
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
