<!-- Modal Add Quotaion-->
<div class="modal fade" id="modalListPending<?php echo $pending_with1;?><?php echo $module1;?>" role="dialog" aria-labelledby="modalListPendingLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="btn btn-danger" >&times;</span>
                </button>
			<?php	
				
				$sql = "select * from sma_user where id = '$pending_with1' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$pending_with1_username  = $r2['username'];
				
				if($module1=='PR'){
					$module_nm = 'MRN';
				}
				else if($module1=='GR'){
					$module_nm = 'GRN';
				}
				else if($module1=='AP'){
					$module_nm = 'Approval Memo';
				}
				else if($module1=='PO'){
					$module_nm = 'Purchase Order';
				}	
				else if($module1=='SI'){
					$module_nm = 'Invoice Against GRN';
				}
				else if( $module1=='CE'  ){
					$module_nm = 'Invoice Against OpEx';
				}
				else if( $module1=='TE'  ){
					$module_nm = 'Travel Expenses';
				}
				else if( $module1=='RE' ){
					$module_nm = 'Regular Expenses';
				}
				else if($module1=='PY'){
					$module_nm = 'Payment';
				}
				else if($module1=='PC'){
					$module_nm = 'Petty Cash';
				}
				else if($module1=='IP'){
					$module_nm = 'IPC';
				}
				else if($module1=='TA'){
					$module_nm = 'Traval Approval';
				}
				
			?>	
                <h4 class="modal-title" id="modalListPendingLabel"><b><?=  $module_nm ?></b> Pending With <b><?= $pending_with1_username ?></b> </h4>
            </div>
			
    <div class="modal-body">
        <section class="content">
            <div class="row">
				<table id="prtable123" class="table table-bordered table-striped">
                <thead>
                <tr style="background-color:#DDDAD8;">
                    <th></th>
					<th>Doc.ID</th>
                    <th>Dated</th>
					<th>Company</th>
					<th>Type</th>
					<th>Name</th>
					<th style="text-align:right;">Amount</th>
					<th>Sent By</th>
					<th>Pending Days</th>
				</tr>
                </thead>
                <tbody>
				
            <?php //echo $pending_with1;?>
			<?php 
			
				//echo $module1;
			if($module1=='PR'){
				$table_name = 'sma_purchase_req';
			}
			else if($module1=='GR'){
				$table_name = 'sma_supplier_invoice';
			}			
			else if($module1=='AP'){
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
			
			$sql = " SELECT b.*  FROM `analysis_pending` a, $table_name b where a.doc_id = b.id and module = '$module1' and pending_with ='$pending_with1' ";
			$res2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
//echo $sql. "<BR>";					
			while($rw2 = mysqli_fetch_array($res2)){
				
				$st_flag ='';
				if($module1=='AP'){				
					$dated = date('d-m-Y', strtotime($rw2['dated']));
					$overhead_exp		= $rw2['overhead_exp'];
					$company = $rw2['company'];
					$comon_id = $rw2['id'];
					$sql = "SELECT b.party_name, a.values FROM `sma_approval_details` a , `sma_party_mst` b where vendor_selected = 'Y' and approval_hdr_id = '$comon_id' and b.id = a.supplier_name " ;
				
					$ij=0;
					$party_name  = '';
					$amount		 =0;
					$q2  		= mysqli_query($con, $sql);
					$raffect 	= mysqli_affected_rows($con);
					while($r2 	= mysqli_fetch_array($q2)){
								
						if($ij>0){$party_name.=', <BR> ' ;}
						$party_name  .= $r2['party_name'];
						$amount		 += $r2['values'];
						$ij = $ij + 1;
		
					}		
					if($overhead_exp=='Y'){
						$ttype = "For Operating Expense";
					}
					else{
						$ttype = "For PO";
					}
				}
				
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
				
				if( $module1=='PR' ){
					$amount =0;	
					$comon_id 		= $rw2['id'];	
					$dated 			= date('d-m-Y', strtotime($rw2['date']));
					$ttype  	 	= $rw2['pr_number'];
					$supplier_id 	= $rw2['subject'];
					$company 		= $rw2['company_id'];
					$tot_amount = '0';
					$sql="SELECT * from sma_purchase_req_items where purchase_req_id = '$comon_id' ";
					$res1 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					while($r1 = mysqli_fetch_array($res1)){
						$amount 	= $r1['quantity'];
						$tot_amount = $tot_amount + $amount;
					}
					$amount = $tot_amount;
				}
				
				if($module1=='SI' || $module1=='GR'){
					$comon_id 		= $rw2['id'];	
					$dated 			= date('d-m-Y', strtotime($rw2['invoice_date']));
					$ttype  	 	= $rw2['supplier_invoice_no'];
					$supplier_id 	= $rw2['suplier_name'];
					$amount 	 	= $rw2['total_amount'];
					$company 		= $rw2['company_id'];
				}
				
				if($module1=='PY'){
					$comon_id 		= $rw2['id'];	
					$dated 			= date('d-m-Y', strtotime($rw2['dated']));
					$ttype  	 	= $rw2['supplier_invoice_no'];
					$supplier_id 	= $rw2['paid_to'];
					$st_flag 		= $rw2['st_flag'];
					$amount 	 	= $rw2['total_amount_paid'];
					$company 		= $rw2['company_id'];
				}
				
				if($module1=='TA'){
					$amount = 0;
					$comon_id 		= $rw2['id'];	
					$dated 			= date('d-m-Y', strtotime($rw2['dated']));
					$ttype  	 	= '';
					$supplier_id 	= $rw2['emp_id'];
					$company 		= $rw2['company_id'];
				}
				
				if($module1=='RE' || $module1=='TE' || $module1=='CE' ){
					//|| $module1=='TA'
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
				
				if($module1=='PC' ){
					$comon_id 		= $rw2['id'];	
					$company 		= $rw2['company_id'];
					$amount 		= $rw2['total_amount'];
					$dated 			= date('d-m-Y', strtotime($rw2['dated']));
					$sql = "SELECT * FROM `sma_pettycash_exp` where approval_ref_no = '$comon_id' ";
					$q2  = mysqli_query($con, $sql);
					$r2  = mysqli_fetch_array($q2);
					
					$paid_to  		= $r2['paid_to'];
					$supplier_id  	= $r2['spend_by'];
					$ttype		  	= $r2['invoice_no'];
				}
				
				if($module1=='PO' || $module1=='SI' || $module1=='GR' || $module1=='CE' || $module1=='PY' || ( $module1=='PC' && $paid_to=='V') ){
					$sql 	= "select * from sma_party_mst where id = '$supplier_id' ";
//echo $sql."<BR>";;					
					$q2 	= mysqli_query($con, $sql);
					$r2 	= mysqli_fetch_array($q2);
					$party_name 	= $r2['party_name'];
//echo $party_name. ' ' . $supplier_id. "<br>";			
					
				}
				
				if($st_flag =='A' || $st_flag =='T' || $module1=='TA' || $module1=='RE' || $module1=='TE' || ( $module1=='PC' && $paid_to=='U') ){
					$sql = "SELECT * FROM `sma_user` where id = '$supplier_id' ";
					$q2  = mysqli_query($con, $sql);
					$r2  = mysqli_fetch_array($q2);
					$party_name  = $r2['username'];
					
				}
				
				if($paid_to=='O' || $module1=='PR'){
					$party_name  = $supplier_id;
				}
				
				if($dated =='01-01-1970'){
					$dated ='';
				}
				
				$sql = "select * from company where comp_id = '$company' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$company_name  	= $r2['comp_name'];
				$company  		= $r2['comp_code'];
				
				$s1  = "SELECT * from workflow_history where doc_id = '$comon_id' and doc_type = '$module1' and reviewed_by = '$pending_with1' and status in ('Submitted', 'Approved' ) order by id desc  ";
//echo $s1."<BR>";				
				$res  = mysqli_query($con, $s1);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($res);
				$create_by		= $r1['create_by'];
				$create_date_prn= date('d-m-Y', strtotime($r1['create_date']));
				if($create_date_prn=='01-01-1970'){
					$create_date_prn='';
					continue;
				}	
				$create_date	= $r1['create_date'];
				$today_date		= date('Y-m-d');
				$datediff		= strtotime($today_date) - strtotime($create_date);
				$days 			= round($datediff / (60 * 60 * 24)) + 1;					
									
				$sl="SELECT * FROM sma_user where id = '$create_by' ";
				$r3 = mysqli_query($con, $sl);
				$rw = mysqli_fetch_array($r3);
				$sent_by = $rw['username'];
									
				$jj = $jj + 1;
				
		?>		
				<tr>
	
				<td width="0%" ><input type="hidden" value="<?php echo $jj;?>" > </td>
				<td width="4%" ><?php echo $comon_id;?></td>
				<td width="12%" ><?php echo $dated;?></td>
				<td width="10%" style="text-align:left;" ><?php echo $company;?></td>
				<td width="15%" style="text-align:left;"><?php echo $ttype;?></td>
					
				<td width="30%" style="text-align:left;"  ><?php echo $party_name;?></td>
				<td width="10%" style="text-align:right;" ><?php echo moneyFormatIndia($amount);?></td>
				<td width="20%" ><?php echo $sent_by."<BR>".$create_date_prn;?></td>
				<td width="10%" ><?php echo $days;?></td>
				
				</tr>
		
		<?php
			}
		?>
			</tbody>
                <tfoot>
                
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

