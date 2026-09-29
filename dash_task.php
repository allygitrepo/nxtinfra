<?php

// Pending Task
//Task
//if(isset($_POST['sub51'])){
    
?>

		
<?php
						
							$doc_type	= 'TK';
								
							$rcnt_pc = 0;
							$pending_cnt 	= '0';
							$submit_cnt 	= '0';
							$completed_cnt 	= '0';
					
					/* $sql = " SELECT b.id as payment_id,
							IF(a.status='S' , count(a.status) ,'') as status_submit,
							IF(a.status='P' , count(a.status) ,'') as status_pending,
							IF(a.status='C' , count(a.status) ,'') as status_completed
							FROM task_workflow a, payment_header b 
							where b.id = a.payment_id and b.company_id in ($comid) and
							a.id in ( SELECT max(a.id) FROM `task_workflow` a, payment_header b 
									where b.id = a.payment_id and b.company_id in ($comid) 
										group by doc_id, create_by )
							group by a.status , b.id "; */
							
					$sql = "select * from sma_pending_task a, payment_header b where 1 and a.payment_id = b.id and b.company_id in ($comid) ";
//echo $sql;								
					//$sql .= ' order by payment_id desc ';						

		$modulePath1 = 'payment/';
			
		?>
		<div class="col-md-12">
			<div class="box123"> </div>	
				
              <table id="prtable123" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th></th>
					
					<td style="text-align:center;font-size:18px;">Pending</td>
					<td style="text-align:center;font-size:18px;">Submitted</td>
					<td style="text-align:center;font-size:18px;">Completed</td>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$payment_id  		 = $row['payment_id'];
		$status_task  		 = $row['status_task'];
		
		
		/* $status_submit  	 = $row['status_submit'];
		$status_pending  	 = $row['status_pending'];
		$status_completed  	 = $row['status_completed'];
		 */
		/* $sql = " SELECT max(id) FROM `task_workflow` where status='C' and payment_id = '$payment_id ' group by status, doc_id ";
//echo $sql."<BR>";		
		$q3 = mysqli_query($con, $sql);
		$rowaffected = mysqli_affected_rows($con);
		if($rowaffected >0){
			$status_submit = $status_submit - 1; 
		} */
		
		$sql = "select a.*, c.module_name, c.module_code, b.st_flag , d.supp_id
					from sma_pending_task a, payment_header b, sma_module c , payment_details d
						where b.id = a.payment_id and c.id = a.document_id and b.id = d.payment_hdr_id and a.payment_id = '$payment_id' 
							order by a.id  ";
		
//echo $sql;		
		$q3 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r3 = mysqli_fetch_array($q3);
		$supp_id			= $r3['supp_id'];
		$module_code		= $r3['module_code'];
		$st_flag			= $r3['st_flag'];
		
		if($st_flag=='S' || $st_flag=='R'){
			$sql = "SELECT * FROM sma_supplier_invoice where id = '$supp_id' and del!='Y' ";		
			$q3  = mysqli_query($con, $sql);
			$r3  = mysqli_fetch_array($q3);
			$draft_by		= $r3['draft_by'];
		}
		else if($st_flag=='A'){
			$sql = "SELECT * FROM sma_supplier_invoice where id = '$supp_id'  and del!='Y' ";		
			$q3  = mysqli_query($con, $sql);
			$r3  = mysqli_fetch_array($q3);
			$our_po_ref_no 	 = $r3['our_po_ref_no'];
			$draft_by		 = $r3['draft_by'];
		}
		else if($st_flag=='D'){
			$sql = "SELECT * FROM sma_purchase_order where id = '$supp_id'  and del!='Y' ";		
			$q3  = mysqli_query($con, $sql);
			$r3  = mysqli_fetch_array($q3);
			$our_po_ref_no 	 	= $r3['our_po_ref_no'];
			$approval_memo_ref 	= $r3['approval_memo_ref'];
			$draft_by			= $r3['draft_by'];
		}
		else if($st_flag=='C' || $st_flag=='R' || $st_flag=='T'){
			$sql = "SELECT * FROM sma_travel_expenses where id = '$supp_id'  and del!='Y' ";		
			$q3  = mysqli_query($con, $sql);
			$r3  = mysqli_fetch_array($q3);
			$approval_ref_no 	 = $r3['id'];
			$draft_by		= $r3['draft_by'];
		}
		else if($module_code=='I'){
			$sql = "SELECT b.* FROM sma_supplier_invoice a, sma_ipc b 
					where a.id = b.sma_invoice_no and a.id = '$supp_id' and b.del !='Y' ";
			$q3  = mysqli_query($con, $sql);
			$r3  = mysqli_fetch_array($q3);
			$draft_by		= $r3['draft_by'];
		}

//echo $sql."<BR>";
															
//echo $draft_by . ' == ' . $user . ' ' . $role;
		$aok ='';
		if( $draft_by == $user || $user=='Admin' || $role =='Accountant' || $role=='CXO'){
			$aok='Y';
		}
		else {
			continue;
		}		
		/* if( $draft_by == $user || $user=='Admin' || $role =='Accountant' || $role=='CXO' ){
			$a='';
		}
		else {
			//$pending_cnt 	= $pending_cnt - 1;
			continue;
		}
		 */
		
		if($status_task=='P'){
			$pending_cnt = $pending_cnt  + 1;
		}
		else if($status_task=='S'){
			$submit_cnt = $submit_cnt  + 1;
		}
		else if($status_task=='C'){
			$completed_cnt = $completed_cnt  + 1;
		}
		
		$baseurl1 	= $baseurl.$modulePath1.'task_status_change.php?sub=edit&payment_id='.$payment_id;
				
	?>
	
	<?php }?>

<?php 

	for($i=0;$i<20;$i++){
		$sps .= '&nbsp;';
	}
?>
		<tr>
			<td width="10%" style='background-color:%#B97953;color:black;text-align:center;font-size:18px;'>TASK</td>
			
			<?php
				$baseurla = '#';
				if($pending_cnt>0){
					$baseurla = $baseurl . $modulePath1 . "task_status_change.php?sub=edit&status=P";
				}
			?>
			<td width="10%" style='background-color:pink;color:white;text-align:center;'>
				<a href="<?php echo $baseurla;?>" style="color:black;font-size:18px;" target="_blank"><?php echo $sps. $pending_cnt .$sps;?>
				</a>
			</td>
			<td width="10%" style='background-color:powderblue;color:white;text-align:center;'>
				<?php
				$baseurla = '#';
				if($submit_cnt>0){
					$baseurla = $baseurl . $modulePath1 . "task_status_change.php?sub=edit&status=S";
				}
				?>
				<a href="<?php echo $baseurla;?>" style="color:black;font-size:18px;" target="_blank"><?php echo $sps. $submit_cnt .$sps;?>
				</a>
			</td>
			<?php if($role!='Accountant' && $role1='Maker'){ 
				if($completed_cnt>0){
					$baseurla = $baseurl . $modulePath1 . "task_status_change.php?sub=edit&status=C";
				}
			?>
			<td width="10%" style='background-color:orange	;color:white;text-align:center;'>
				<a href="<?php echo $baseurla ?>" style="color:black;font-size:18px;" target="_blank">
					<?php echo $sps. $completed_cnt .$sps;?>
				</a>
			</td>
			<?php
			}
			else if( $role=='Accountant' || $user=='Admin' || $role=='Maker' ){ 
				$baseurla = '#';
				if($completed_cnt>0){
					$baseurla = $baseurl . $modulePath1 . "task_status_change.php?sub=edit&status=C";
				}
				else {$completed_cnt=0;}
			?>
			<td width="10%" style='background-color:orange	;color:white;text-align:center;'>
				<a href="<?php echo $baseurla ?>" style="color:black;font-size:18px;" target="_blank">
					<?php echo $sps. $completed_cnt .$sps;?>
				</a>	
			</td>
			<?php } ?>
			
		</tr>
		
            </tbody>
        </table>
		<div class="box"> </div>	
    </div>

<?php
//}
//End Task	