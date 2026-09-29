<?php if($_GET['sub'] == 'list'){

	
	include("../dbcon.php");

	
	$prn='excel';
		
	$message = '';
	$message .= "<table border='0' cellspacing='0' style='width: 100%; text-align: center; font-size: 12px;'>
			<tr><td style='width: 80%;;'>Material Master  </td><td> Date:" . date('d-m-Y') ."</td></tr></table>";	
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; ; font-size: 12px;'>
			<tr>
				<th style='width: 5%;text-align: right;'>Sr.No.</th>
				<th style='width: 8%;text-align: left;'>Material Name</th>
				<th style='width: 8%;text-align: left;'>Vertical Type</th>
				<th style='width: 10%;text-align: left;'>Product Group</th>
				<th style='width: 10%;text-align: left;'>PO Threashold</th>
				<th style='width: 10%;text-align: left;'>Category</th>
				<th style='width: 10%;text-align: left;'>Account Name</th>
				<th style='width: 10%;text-align: left;'>Tolarance Level</th>
				<th style='width: 10%;text-align: left;'>GST Type</th>
				<th style='width: 10%;text-align: left;'>UOM</th>
				<th style='width: 8%;text-align: left;'>HSN Code</th>
			</tr>
			</table>";
		
		$message .= "<table border='1' cellspacing='0' style='width: 100%; ; font-size: 12px;'>";
		$i =0;
		$sql ='';
		$sql="SELECT name, vertical_type, po_threashold, product_group, category, uom, tolerance_level, gst_type, account_id, hsn_code from sma_product where 1 order by vertical_type, product_group, category, name ";
//echo $sql;		name, vertical_type, po_threashold, product_group, category, uom gst_Type, account_id, hsn_code
		$result = mysqli_query($con,$sql);
		while($row = mysqli_fetch_array($result)){
						
			$name			= $row['name'];
			$vertical_type	= $row['vertical_type'];
			$group			= $row['product_group'];
			$po_threashold	= $row['po_threashold'];
			$category		= $row['category'];
			$uom			= $row['uom'];
			$tolerance_level= $row['tolerance_level'];
			$gst_type		= $row['gst_type'];
			$account_id		= $row['account_id'];
			$hsn_code		= $row['hsn_code'];
			
			if($category=='M'){
				$category = 'Material';
			}
			else if($category=='S'){
				$category = 'Service';
			}
			
			if($po_threashold=='Q'){
				$po_threashold = 'Qty';
			}
			else if($po_threashold=='V'){
				$po_threashold='Value';
			}	
			$vertical_type = $row['vertical_type'];
			$sql= "SELECT * FROM sma_vertical where id = '$vertical_type' ";
			$q2 = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$vertical_type_name = $r2['vertical_name'];
			
			$product_group = $row['product_group'];
			$sql= "SELECT * FROM sma_product_group where id = '$product_group' ";
			$q2 = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$product_group = $r2['product_group'];
			
			$sql = "select * from gst_mst where id = '$gst_type' and vertical_type = '$vertical_type' ";
			$q22 = mysqli_query($con, $sql);
			$r22 = mysqli_fetch_array($q22);
			$gst_name =  $r22['gst_name'];
			
			$account_name = '';
			$sql = "select * from account_mst where 1 and vertical_type = '$vertical_type' and id = '$account_id' ";
			$q22 = mysqli_query($con, $sql);
			$r22 = mysqli_fetch_array($q22);
			$account_name = $r22['account_name'];
			
			$i = $i +1;	
			$message .= "<tr>
				<td style='width: 5%;text-align: right;'>".$i."</td>
				<td style='width: 25%'>".$name."</td>
				<td style='width: 10%'>". $vertical_type_name."</td>
				<td style='width: 15%'>". $product_group."</td>
				<td style='width:10%' >". $po_threashold."</td>
				<td style='width:10%' >". $category."</td>
				<td style='width:10%' >". $account_name."</td>
				<td style='width:10%' >". $tolerance_level."</td>
				<td style='width:10%' >". $gst_name."</td>
				<td style='width:10%;text-align:left'>". $uom."</td>
				<td style='width:10%' >". $hsn_code."</td>	
				</tr>";
	}
			
		$message .= "</table>";
	
//echo $message;
//exit();

    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();

	if($prn=='excel'){
		header("Content-type: application/xls");
		Header("Content-Disposition: attachment; filename=product_master.xls");
		print $message;
		
		/* $flname = 'product_master.xls';
		$fp = fopen($flname, 'w');
		fwrite($fp,$message);
		fclose($fp);
		 */
		/* echo '<a href="'.$flname.'" target="_blank"> Process Done...Click here for download file</a>';
		echo '<br><br><br>';
		$baseurl1 = $baseurl."dashboard.php";
		echo "<a href='$baseurl1' > Go to Dashboard...Back</a>"; */
		
	}
	
    // convert to PDF
	if($prn == 'pdf'){
		require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		try
		{
			$html2pdf = new HTML2PDF('L', 'A4', 'fr');
			$html2pdf->pdf->SetDisplayMode('fullpage');
	//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
		   // $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$html2pdf->writeHTML($message);
			$html2pdf->Output('vendor_master.pdf');
			
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
	}
 }	?>
	


