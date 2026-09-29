<?php

?>
<!-- Modal Add Quotaion-->
<div class="modal fade" id="modalListUnpaid<?php echo $company_with1;?><?php echo $module1;?>" role="dialog" aria-labelledby="modalListUnpaidLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="btn btn-danger" >&times;</span>
                </button>
			<?php	
				
				$sql = "select * from company where comp_id = '$company_with1' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				//$company_with1_username  = $r2['comp_name'];
				$company_with1_name  = $r2['comp_code'];
				
				if($module1=='PO'){
					$module_nm = 'Purchase Order';
				}	
				else if($module1=='SI'){
					$module_nm = 'Supplier Invoice';
				}
				else if( $module1=='CE'  ){
					$module_nm = 'Operating Expenses';
				}
				else if( $module1=='TE'  ){
					$module_nm = 'Travel Expenses';
				}
				else if( $module1=='RE' ){
					$module_nm = 'Regular Expenses';
				}
			?>	
                <h4 class="modal-title" id="modalListUnpaidLabel"><b><?=  $module_nm ?></b> Unpaid List for <b><?= $company_with1_name ?></b> </h4>
				
            </div>
			
    <div class="modal-body">
        <section class="content">
            <div class="row">
				<table id="prtable123" class="table table-bordered table-striped">
                <thead>
				<tr><th colspan='4'></th><th colspan='5' style="text-align:right;">PVC=>>Payment Voucher Creation</th>
                <tr style="background-color:#DDDAD8;">
                    <th></th>
					<th>Doc.ID</th>
                    <th>Dated</th>
					<th>Company</th>
					<th>Type</th>
					<th> Name</th>
					<th style="text-align:right;">Amount</th>
					<th>Sent By</th>
					<th>Pending Days</th>
					<th>Status</th>
				</tr>
                </thead>
                <tbody>
				
            <?php //echo $company_with1;?>
			<?php 
			
				//echo $module1;
			if($module1=='AP'){
				$table_name = 'sma_approval_memo';
			}
			else if($module1=='PO'){
				$table_name = 'sma_purchase_order';
			}	
			else if($module1=='SI'){
				$table_name = 'sma_supplier_invoice';
			}
			else if( $module1=='CE' || $module1=='TE' || $module1=='RE' ){
				$table_name = 'sma_travel_expenses';
			}
			else if($module1=='PY'){
				$table_name = 'payment_header';
			}
			else if($module1=='PC'){
				$table_name = 'sma_pettycash';
			}
			else if($module1=='IP'){
				$table_name = 'sma_ipc';
			}
			else if($module1=='TA'){
				$table_name = 'sma_traval_approval';
			}
			$total_amount =0;
			$sql = " SELECT b.*  FROM `analysis_unpaid` a, $table_name b where a.doc_id = b.id and module = '$module1' and company_with ='$company_with1' and del !='Y' ";
//echo $sql."<BR>";			
			$res2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
					
			while($rw2 = mysqli_fetch_array($res2)){
				
				if($module1=='PO'){
					$comon_id = $rw2['id'];
					$company = $rw2['project'];
					$supplier_id = $rw2['to_supplier'];
					$purchase_id = $rw2['id'];
					$tot_amount = '0';
					$sql="SELECT * from sma_po_items where purchase_id = '$purchase_id' ";
					$res1 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					while($r1 = mysqli_fetch_array($res1)){
						$qty 	= $r1['quantity'];
						$rate 	= $r1['unit_rate'];
						$gst	= $r1['gst'];
						$amount = round($qty * $rate + ((($qty * $rate) * $gst) / 100),0);
						$tot_amount = $tot_amount + $amount;
					}
					$amount = $tot_amount;
					$ttype  = $rw2['po_number'];
					
					$dated = date('d-m-Y', strtotime($rw2['dated']));
					
				}
				
				if($module1=='SI'){
					$comon_id 		= $rw2['id'];	
					$dated 			= date('d-m-Y', strtotime($rw2['invoice_date']));
					$ttype  	 	= $rw2['supplier_invoice_no'];
					$supplier_id 	= $rw2['suplier_name'];
					$amount 	 	= $rw2['total_amount'];
					$company 		= $rw2['company_id'];
				}
				
				if($module1=='RE' || $module1=='TE' || $module1=='CE'){
					$comon_id 		= $rw2['id'];	
					$dated 			= date('d-m-Y', strtotime($rw2['dated']));
					$ttype  	 	= '';
					$supplier_id 	= $rw2['emp_id'];
					$company 		= $rw2['company_id'];
					if($module1=='RE' ){
						$exptype = 'R';	
					}
					if($module1=='TE' ){
						$exptype = 'T';	
					}
					if($module1=='CE' ){
						$exptype = 'C';	
					}					
					$sql  = "SELECT sum(amount) as amount FROM `sma_expenses` where exp_type = '$exptype' and approval_ref_no = '$comon_id' ";
					$res1 	= mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 	= mysqli_fetch_array($res1);
					$amount			= $r1['amount'];
					
				}
				
				if($module1=='PO' || $module1=='SI' || $module1=='CE'){
					$sql 	= "select * from sma_party_mst where id = '$supplier_id' ";
					$q2 	= mysqli_query($con, $sql);
					$r2 	= mysqli_fetch_array($q2);
					$party_name 	= $r2['party_name'];
				}
				else if($st_flag =='A' || $st_flag =='T' || $module1=='RE' || $module1=='TE' || $module1=='PC' ){
					$sql = "SELECT * FROM `sma_user` where id = '$supplier_id' ";
					$q2  = mysqli_query($con, $sql);
					$r2  = mysqli_fetch_array($q2);
					$party_name  = $r2['username'];
				}
				
				if($dated =='01-01-1970'){
					$dated ='';
				}
				
				$sql = "select * from company where comp_id = '$company' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$company_name  	= $r2['comp_name'];
				$company  		= $r2['comp_code'];
				
				$s1  = "SELECT * from workflow_history where doc_id = '$comon_id' and doc_type = '$module1' and status in ('Submitted', 'Approved' ) order by id desc  ";
//echo $s1. "<BR>";				
				$res  = mysqli_query($con, $s1);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($res);
				$create_by		= $r1['create_by'];
				$create_date_prn= date('d-m-Y', strtotime($r1['create_date']));
				$create_date	= $r1['create_date'];
				$today_date		= date('Y-m-d');
				$datediff		= strtotime($today_date) - strtotime($create_date);
				$days 			= round($datediff / (60 * 60 * 24)) + 1;					
									
				$sl="SELECT * FROM sma_user where id = '$create_by' ";
				$r3 = mysqli_query($con, $sl);
				$rw = mysqli_fetch_array($r3);
				$sent_by = $rw['username'];
									
				//$amount 	 	= $rw2['total_amount'];
				$status = '';
				$stflag = '';
				if( $module1=='RE' || $module1=='TE' || $module1=='CE' || $module1=='SI' ){
					if( $module1=='RE' || $module1=='TE' ){
						$stflag = 'T';
					}
					else if ( $module1=='CE' ){
						$stflag = 'C';
					}
					else if ( $module1=='SI' ){
						$stflag = 'S';
					}	
					
					$sql = "SELECT a.* FROM `payment_header` a, payment_details b 
						WHERE a.id = b.payment_hdr_id and supp_id = '$comon_id' 
							and a.st_flag = '$stflag' and a.del !='Y' ";
					$q2  			= mysqli_query($con, $sql);
					$py_raffect 	= mysqli_affected_rows($con);
					if($py_raffect==0){
						$status = 'PVC Pending';		
					}
					else {
						$sql = "SELECT a.* FROM `payment_header` a, payment_details b 
							WHERE a.id = b.payment_hdr_id and supp_id = '$comon_id' 
								and a.st_flag = '$stflag' and a.del !='Y' ";
						$qry2  			= mysqli_query($con, $sql);
						echo mysqli_error($con);					
						$rwa = mysqli_fetch_array($qry2);
						$utr_no 		= $rwa['utr_no'];
						if(empty($utr_no)){
							$status 		= 'UTR Blank';
						}
					}
				}
				
				$jj = $jj + 1;
				
		?>		
				<tr>
	
				<td width="1%" ><input type="hidden" value="<?php echo $jj;?>" > </td>
				<td width="4%" ><?php echo $comon_id;?></td>
				<td width="12%" ><?php echo $dated;?></td>
				<td width="10%" style="text-align:left;" ><?php echo $company;?></td>
				<td width="15%" style="text-align:left;"><?php echo $ttype;?></td>
					
				<td width="20%" style="text-align:left;"><?php echo $party_name;?></td>
				<td width="10%" style="text-align:right;" ><?php echo moneyFormatIndiaa($amount);?></td>
				
				<td width="20%" ><?php echo $sent_by."<BR>".$create_date_prn;?></td>
				<td width="10%" ><?php echo $days;?></td>
				<td width="10%" ><?php echo $status;?></td>
				</tr>
				
		<?php
			 	$total_amount = $total_amount + $amount;
			}
		?>
			</tbody>
                <tfoot>
                <tr>
	
				<td width="1%" ></td>
				<td width="4%" ></td>
				<td width="12%" ></td>
				<td width="10%" ></td>
				<td width="15%" ></td>
					
				<td width="30%" style="text-align:right;">Total</td>
				<td width="10%" style="text-align:right;" ><?php echo moneyFormatIndiaa($total_amount);?></td>
				
				<td ></td>
				<td ></td>
				</tr>
				
                </tfoot>
            </table>	
			  
                </div>
				
				<button type="button" class="close1"  data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="btn btn-danger" >Close</span>
                </button>
				
				</section>
				
            </div>

			
				
            </div>
        </div>
</div>


<?php 
?>