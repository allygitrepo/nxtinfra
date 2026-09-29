<?php 
	
session_start();
	
if($_GET['sub'] == 'pdf'){

	include("../dbcon.php");
		
	$message = '';
	$message .= "<table border='0' cellspacing='0' style='width: 100%; text-align: center; font-size: 12px;'>
			<tr><td style='width: 80%;;'>Product wise Opening Stock </td><td> Date:" . date('d-m-Y') ."</td></tr></table>";	
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; ; font-size: 12px;'>
			<tr>
				<th>Srno.</th>
				<th>Product Name</th>
				<th>Product Group</th>
				<th>UOM</th>
				<th>Company Name</th>
				<th  style='text-align:right;'>Opening Stock</th>
				<th  style='text-align:right;'>Receipts</th>
				<th  style='text-align:right;'>Issue</th>
				<th style='text-align:right;'>Closing Stock</th>
				<th style='text-align:right;'>Average Rate</th>
				<th style='text-align:right;'>Total Amount</th>
			</tr>
			</table>";
		
		$message .= "<table border='1' cellspacing='0' style='width: 100%; ; font-size: 12px;'>";
		$i =0;
		$sql ='';
	//	$sql="SELECT * from sma_product where 1 order by vertical_type, product_group, category, name ";
		
		$sql = $_SESSION['sqlpros'];

		$result = mysqli_query($con,$sql);
		while($row = mysqli_fetch_array($result)){
						
			$project 	= $row['project'];
			$company_id = $row['project'];
			$sql = "SELECT * from company where comp_id = '$project' ";
			$res = mysqli_query($con, $sql);
			//echo mysqli_error($con);
			$r2 = mysqli_fetch_array($res);
			$comp_name 	= $r2['comp_name'];
			$project 	= $r2['comp_code'];

			$product_name = $row['product_name'];
			$product_id	  = $row['product_name'];
			$sql 	= "select * from sma_product where id = '$product_name' ";
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$product_name 	= $r2['name'];
			$unit 			= $r2['uom'];
			$category		= $r2['category'];
			$product_group		= $r2['product_group'];
			$do_include_stock	= $r2['do_include_stock'];
			if($do_include_stock=='Y'){
			    continue;
			}
			
			$sql 	= "select * from sma_product_group where id = '$product_group' ";
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$product_group 	= $r2['product_group'];
			
			
			$opening_stock 	= $row['opening_stock'];
			$receipts 		= $row['receipts'];
			$issue			= $row['issue'];
			
			$closing_stock = ($opening_stock + $receipts ) - ( $issue ); 
			$total_stock   = ($opening_stock + $receipts ) ; 

			$average_rate	='';
			$total_value	='';
			if($category=='M'){
				$sql = "select material_id, round(( total_value / total_qty ),2) as average_rate, total_qty from (
					SELECT material_id, round(sum((qty * rate) + (qty * rate) * gst / 100 ),2) total_value , sum(qty) total_qty , qty, gst,rate FROM `sma_supplier_invoice_details` a, sma_supplier_invoice b where 1 and b.id = a.si_hdr_id and b.del !='Y' and b.approval_status != 'Rejected' and material_id = '$product_id' ) DS ";
				$res 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($res);
				$average_rate 	= $r2['average_rate'];
				$total_value	= round($total_stock * $average_rate,2);
				
			}
			else if($category=='S'){
				$sql = "select material_id, round(( total_value / total_qty ),2) as average_rate, total_qty from (
					SELECT material_id, round(sum((qty * rate) + (qty * rate) * gst / 100 ),2) total_value , (qty) as total_qty , qty, gst,rate FROM `sma_supplier_invoice_details` a, sma_supplier_invoice b where 1 and b.id = a.si_hdr_id and b.del !='Y' and b.approval_status != 'Rejected' and material_id = '$product_id' ) DS ";
				$res 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($res);
				$average_rate 	= $r2['average_rate'];
				$total_value	= round($total_stock * $average_rate,2);
				
			}
			//echo $average_rate;
			
			if($total_value<=0 ){
				$sql = " SELECT sum(total_value) as total_value , sum(receipt_qty) as total_stock, sum(total_value)/ sum(receipt_qty) as rate 
							FROM `sma_goods_receipt_note` a, `sma_goods_receipt_note_items` b 
								WHERE b.grn_hdr_id = a.id AND total_value >0 AND company_id = '$company_id' AND b.product_id = '$product_id' ";
				$res 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($res);	
				
				$total_value	= round($r2['total_value'],2);
				$total_stock	= round($r2['total_stock'],2);
				if($total_value!=0){
					$average_rate 	= round($total_value / $total_stock,2);
				}
			}
	
			if($average_rate=='NAN'){
				$average_rate = 'Del';
			}		
			$i = $i +1;	
			$message .= "<tr>
				<td style='width: 5%;text-align: right;'>".$i."</td>
				<td style='width: 25%'>".$product_name."</td>
				<td style='width: 25%'>".$product_group."</td>
				<td style='width: 15%'>". $unit."</td>
				<td style='width:10%' >". $project."</td>
				<td style='width:10%;text-align:right;' >". $opening_stock."</td>
				<td style='width:10%;text-align:right;' >". $receipts."</td>
				<td style='width:10%;text-align:right;' >". $issue."</td>
				<td style='width:10%;text-align:right'>". $closing_stock."</td>
				<td width='08%' style='text-align:right;'>".number_format($average_rate,2)."</td>
				<td width='10%' style='text-align:right;'>".number_format($total_value,2)."</td>
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

	
		header("Content-type: application/xls");
		Header("Content-Disposition: attachment; filename=product_open_stock.xls");
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
 
 