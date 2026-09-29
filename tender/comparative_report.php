<?php
	
	include("../dbcon.php");

	$tender_id = $_GET['tender_id'];
	
	$sql  = "SELECT * from workflow_history where doc_id = '$tender_id' and doc_type = 'TN' order by id ";		
	$res  = mysqli_query($con, $sql);
	
	echo mysqli_error($con);
	$r1 = mysqli_fetch_array($res);
	$create_by		= $r1['create_by'];
	$created_date	= date('d-m-Y', strtotime($r1['create_date']));
	if($created_date=='01-01-1970'){
			$created_date ='';
	}
									
	$sql="SELECT * FROM sma_user where id = '$create_by' ";
	$r3 = mysqli_query($con, $sql);
	$rw = mysqli_fetch_array($r3);
	$create_by = $rw['username'];

//Header Start									
	$message = '<br><br>';
	
	$message .= "<p style='text-align: center; font-size: 14pt;'>Comparative Chart</p>";
	
	$message .= "<p style='text-align: center; font-size: 13pt;'>Tender Number : ". $tender_id. "&nbsp;&nbsp; Created By: ".$create_by. "&nbsp;&nbsp; Dated: ".$created_date."</p>";
	
	$s1  = " SELECT distinct(a.supplier_id ) as supplier_id
			FROM `sma_tender_supplier_quote` a, sma_tender_items b 
				WHERE b.id = a.`tender_item_id` and b.tender_hdr_id = '$tender_id' and a.supplier_id >0  order by a.supplier_id ";
	//echo $s1;		
	$res  = mysqli_query($con, $s1);
	echo mysqli_error($con);
	while($r1 = mysqli_fetch_array($res)){;
		$supplier_id		= $r1['supplier_id'];
									
		$supplier_arr[]		= $r1['supplier_id'];
										
		$sql 	= " select * from sma_party_mst where id = '$supplier_id' "; 
		$q2		=	mysqli_query($con, $sql);
		$r2 	=	mysqli_fetch_array($q2);
		$party_name_arr[]		= $r2['party_name'];
										
	}
//Header End
	
	$message .= "<table border='0.5' cellspacing='0' style='width: 100%; text-align: center; margin-left:10px;font-size: 10pt;'>	
				<tr>
			  <th width='20%' >Product</th>	
			  <th width='10%' >Description</th>	
			  
			  <th width='10%' style='padding: 3px;' >Quantity</th>	
			  <th width='10%' style='padding: 3px;' >UOM</th>	";
		for($i = 0; $i < sizeof($party_name_arr); $i++) { 
			$message .= "<th width='10%' style='overflow-wrap: break-all;padding: 3px;'>".wordwrap($party_name_arr[$i],25,'<br>')."</th>";
		}
	 $message .= "</tr>";
							
	$sql  = " SELECT distinct(b.material_id ) as material_id , a.quantity, b.material_desc
				FROM `sma_tender_supplier_quote` a, sma_tender_items b 
				WHERE b.id = a.`tender_item_id` and b.tender_hdr_id = '$tender_id' and supplier_id >0  order by a.supplier_id, b.material_id";
//echo $sql. "<BR>";										
	$ress  = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($r11 = mysqli_fetch_array($ress)){
				//$supplier_id_var	= $r11['supplier_id'];
		$material_id		= $r11['material_id'];
		$quantity			= $r11['quantity'];
		$material_desc			= $r11['material_desc'];
										
		$sql= " select * from sma_product where id = '$material_id' "; 
		$q2	= mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$product_name		= $r2['name'];	
		$uom				= $r2['uom'];
										
		$message .= "<tr>
					<td width='20%' style='overflow-wrap: break-all;padding: 3px;' > ".wordwrap($product_name,20,'<br>')."</td>
					<td width='10%' style='overflow-wrap: break-all;padding: 3px;' > ".wordwrap($material_desc,10,'<br>')."</td>
					<td width='08%'> $quantity</td>
					<td width='08%'> $uom</td>";
											
		$supplier_id_arr	= array();
		$rate_arr 			= array();
		$quantity_arr		= array();
											
		$material_id_arr	= array();
		$sql  = " SELECT a.supplier_id, a.tender_hdr_id, a.quantity, a.rate, a.gst, b.material_id  
					FROM `sma_tender_supplier_quote` a, sma_tender_items b 
					WHERE b.id = a.`tender_item_id` and b.tender_hdr_id = '$tender_id' and b.material_id = '$material_id' and supplier_id >0 order by supplier_id ";

			$res  = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($r1 = mysqli_fetch_array($res)){
				$supplier_id_arr[]		= $r1['supplier_id'];
				$material_id_arr[]		= $r1['material_id'];
				$rate_arr[]				= $r1['rate'];
				$gst_arr[]				= $r1['gst'];
				$quantity_arr[]			= $r1['quantity'];
			}
							
			for($i = 0; $i < sizeof($supplier_id_arr); $i++){
																			
				$rate			= $rate_arr[$i];
				$gst			= $gst_arr[$i];
				$quantity		= $quantity_arr[$i];
				$net_total_arr[$i] 	= round( $net_total_arr[$i] + ( ($rate * $quantity) + (($rate_arr[$i] * $quantity_arr[$i]) * $gst_arr[$i] ) / 100 ) ,2);		
			
				$message .= "<td width='06%' style='text-align:right;padding: 3px;' >". number_format(($rate_arr[$i] * $quantity_arr[$i]) ,2)."</td>";
													
			}
			$message .= "</tr>";
									
		}
									
								
		$message .= "<tr>
					<th width='20%' style='text-align:right;padding: 3px;'>Total </th>
					<th width='10%'></th>
					<td width='08%'></td>
					<th width='08%'></th> ";
					
		$sql = "SELECT supplier_id, round(sum( a.quantity * a.rate ),2) as amount_v, round( sum(( ( a.quantity * a.rate ) * a.gst) / 100), 2) as gst_value 
					FROM `sma_tender_supplier_quote` a, sma_tender_items b 
					WHERE b.id = a.`tender_item_id` and b.tender_hdr_id = '$tender_id' and supplier_id >0 group by supplier_id ";
		$ress  = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r11 = mysqli_fetch_array($ress)){
			$net_total		= $r11['amount_v'] ;
			$message .= "<th width='10%' style='text-align:right;padding: 3px;' >". number_format($net_total,2)."</th>";
		 }
		$message .= "</tr>";
							
		$message .= "<tr>
					<td width='20%' style='text-align:right;padding: 3px;'><b>GST</b></td>
					<th width='10%'></th>
					<td width='08%'></td>
					<th width='08%'></th> ";
						
		$sql = "SELECT supplier_id, round( sum(( ( a.quantity * a.rate ) * a.gst) / 100), 2) as gst_value 
				FROM `sma_tender_supplier_quote` a, sma_tender_items b 
				WHERE b.id = a.`tender_item_id` and b.tender_hdr_id = '$tender_id' and supplier_id >0 
				GROUP BY supplier_id ";
		$ress  = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r11 = mysqli_fetch_array($ress)){
			$gst_value		= $r11['gst_value'];		
			$gst			= $gst_arr[$i];
											
			$message .= "<td width='06%' style='text-align:right;padding: 3px;' >".number_format($gst_value ,2)."</td>";
		}
		$message .= "</tr>";
									
		$supplier_id_arr	= array();
		$rate_arr 			= array();
		$quantity_arr		= array();
										
		$material_id_arr	= array();
		$message .= "<tr>
					<th width='20%' style='text-align:right;padding: 3px;'>Total Value</th>
					<th width='10%'></th>
					<td width='08%'></td>
					<th width='08%'></th> ";
		$sql = "SELECT supplier_id, round(sum( a.quantity * a.rate ),2) as amount_v, round( sum(( ( a.quantity * a.rate ) * a.gst) / 100), 2) as gst_value 
				FROM `sma_tender_supplier_quote` a, sma_tender_items b 
				WHERE b.id = a.`tender_item_id` and b.tender_hdr_id = '$tender_id' and supplier_id >0 group by supplier_id ";
		$ress  = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r11 = mysqli_fetch_array($ress)){
			$net_total		= $r11['amount_v'] + $r11['gst_value'];	
									
			$message .= "<th width='10%' style='text-align:right;padding: 3px;' >".number_format($net_total,2)."</th>";
		}
		$message .= "</tr>";
							
		/* $message .= "<tr>
					 <th width='20%' ></th>	
					 <th width='06%' ></th>	
					 <th width='06%' ></th>	";
		for($i = 0; $i < sizeof($supplier_arr); $i++) {
			$message .= " <th ><a href=". $baseurl.'athaangSI/' . $modulePath. 'edit.php?sub=edit&id='.$tender_id.'&supplier_id='.$supplier_arr[$i]." >View</a></span></th>	";
		}										  
		$message .= "</tr>"; */
//<!-- View Supplier Quote -->
		
		for($i=0;$i<10;$i++){
			$sps .= '&nbsp;';
		}		
//<!-- TERMS Start -->
		
		$message .= "			<tr>
					<th width='20%' >Terms & Conditions</th>
					<th width='10%' >$sps</th>	
					<th width='8%' >$sps</th>
					<th width='8%' >$sps</th>					";	  
											  
		for($i = 0; $i < sizeof($party_name_arr); $i++) {
			$message .= "<th style='padding: 3px;'>".wordwrap($party_name_arr[$i],25,'<br>')."</th>";	
		}
											  
		$message .= "</tr>";
										
		$sql = " SELECT distinct(b.id ) as terms_id , b.terms_conditions, b.tender_hdr_id
				FROM `sma_tender_supplier_terms` a, sma_tender_terms b 
				WHERE b.id = a.`terms_id` and b.tender_hdr_id = '$tender_id' order by b.id ";								
		$ress  = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r11 = mysqli_fetch_array($ress)){
			$terms_conditions		= $r11['terms_conditions'];
			$terms_id				= $r11['terms_id'];
										
			$message .= "<tr>
						<td width='30%' style='overflow-wrap: break-all'>".wordwrap($terms_conditions,30,'<br>')."</td>
						<th width='10%' >&nbsp;</th>	
						<th width='10%' >&nbsp;</th>";
										
			$sql = " SELECT (b.id ) as terms_id , b.terms_conditions, b.tender_hdr_id,  a.terms_flag, a.remarks, a.supplier_id
					FROM `sma_tender_supplier_terms` a, sma_tender_terms b 
					WHERE b.id = a.`terms_id` and b.id = '$terms_id' and b.tender_hdr_id = '$tender_id' order by a.supplier_id, b.id ";
							
			$res  = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($r1 = mysqli_fetch_array($res)){;
				$supplier_id_arr[]		= $r1['supplier_id'];
				$remarks_arr[]			= $r1['remarks'];
									
				$terms_id_arr[]			= $r1['terms_id'];
				$terms_flag_arr[]		= $r1['terms_flag'];
											
			}
									
			for($i = 0; $i < sizeof($supplier_id_arr); $i++) {	
																			
				$terms_flagg			= $terms_flag_arr[$i];
				$remarks_var			= $remarks_arr[$i];
				if($terms_flagg=='A'){
					$terms_flagg = 'N';	
				}
											
				if($terms_flagg == 'N'){
					$terms_flagg = 'No';
				}
				else if($terms_flagg == 'Y'){
					$terms_flagg = 'Yes';
				}
							
				$message .= "<td width='20%' style='text-align:center;overflow-wrap:break-word;padding: 3px;' ><b>".$terms_flagg .'</b><BR>' . wordwrap($remarks_var,25,'<br>') ."</td>";
			}
				$message .= "</tr>";
									
				$terms_flag_arr 		= array();
				$terms_id_arr			= array();
				$supplier_id_arr		= array();
				$remarks_arr			= array();
		}
		$message .= "</table>";
//<!-- TERMS End -->			
										
		$sel_party_name = '';
		$supplier_var 	= '';
		$readonlyd 		= '';
							
		$sql = " select sum(selected_amount) as selectedamount 
					FROM sma_tender_supplier_quote 
					WHERE tender_hdr_id = '$tender_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
			$selectedamount = $r2['selectedamount'];
			if($selectedamount>0){
				$readonlyd = 'READONLY';
			}
			else {
				$readonlyc 		= '';	
			}	
								
		$sql = " select * from sma_tender_supplier_quote where tender_hdr_id = '$tender_id' ";
		$q2 	= mysqli_query($con, $sql);
		while($r2 = mysqli_fetch_array($q2)){
								
			$supplier_var .= $r2['supplier_id'].',';
								
			$selected_vendor = $r2['selected_vendor'];
			if($selected_vendor == 'Y'){
				$sel_party_name  = $r2['supplier_id'];
				$selected_amount = $r2['selected_amount'];
				$selected_remarks= $r2['selected_remarks'];
			}
		}
							
		$supplier_var .= '0';

		//$message .="<br><b>Supplier Selected for Tender</b><br>";
		$sql = "select * from sma_party_mst where id in ($sel_party_name) ";
		$q2 	= mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$sel_party_name = $r2['party_name'];
		
		//$message .= $sel_party_name . ' ' . $selected_amount ."<BR>";
		$message .= "<br><br><table cellspacing='0' style='width: 85%; border:  0px black; text-align: left;margin-left:50px; font-size: 16px; ' border='0.5'>";
		$message .= "<tr>
					<td style='width: 30%;text-align:center;' ><b>Supplier Selected for Tender</b></td>
					<th style='width: 50%;text-align:center;padding: 3px;' >$sel_party_name</th>	
					<td style='width: 20%;text-align:right;padding: 3px;' ><b>".number_format($selected_amount,2)."</b></td> 
					</tr>
					<tr><td colspan='3' style='width: 100%;text-align:center;'>Remarks : ".	$selected_remarks. "</td></tr>
					</table>";	
		
		
//echo $message ;
//exit();
	
// get the HTML
    ob_start();
	
	$fl_name = "comparative_chart_".$tender_id.".pdf";
		require_once('../html2pdf/html2pdf.class.php');
		try
		{
			$html2pdf = new HTML2PDF('L', 'A4', 'fr');
			$html2pdf->pdf->SetDisplayMode('fullpage');
	//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
		    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$html2pdf->writeHTML($message);
			$html2pdf->Output($fl_name);
			
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
		
?>		