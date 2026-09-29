<?php
//include("header.php");

?>
<!-- DataTables -->
<!--<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">-->

  <!-- Content Wrapper. Contains page content 
  <div class="content-wrapper">-->
    <!-- Content Header (Page header) -->
    <!-- Main content -->
	
    <section class="content">
    	
		
	<?php						
		$comid  = $_SESSION['comid'];
		$role	= $_SESSION['role'];
		$user_category	= $_SESSION['user_category'];
		$usrid  = $_SESSION['usrid'];
			
		$rowcount =1;
//		if ($rowcount > 0){
			$modulePath1 = "supp_invoice/";
	?>

<div class="row">
        <div class="col-xs-12">
          <div class="box">
		  
		  <div class="box-body">
		  
							<?php
								$pcnt = 0;
								if ( $role =='Accountant' || $role =='Checker - Account' || $role =='HOD - Account' || $role =='Checker' || $role =='Maker' ){
									$sql="SELECT approval_status, count(*) as cnt from sma_supplier_invoice where company_id in ( $comid ) and  approval_status in('Pending', 'Verified') 
										and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'SI') 
										or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'SI') or draft_by = '$user' ) group by approval_status ";
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_supplier_invoice` where company_id in ($comid) and approval_status in('Pending', 'Verified')  and draft_by = '$user' group by approval_status";
								}
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt = $r1['cnt'];
								}
									
								$acnt = 0;
								if ($role =='Accountant' || $role =='Checker - Account' || $role =='HOD - Account'  || $role =='Checker'  || $role =='Maker' ){
									$sql="SELECT approval_status, count(*) as cnt from sma_supplier_invoice where company_id in ( $comid ) and  approval_status in('Approved') 
										and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'SI') 
										or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'SI') or draft_by = '$user' )  group by approval_status ";
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_supplier_invoice` where company_id in ($comid) and approval_status = 'Approved' and draft_by = '$user' group by approval_status";
								}
								//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_supplier_invoice` where approval_status = 'Approved'  and draft_by = '$user'  group by approval_status";
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$acnt = $r1['cnt'];
								}
								
								$rcnt = 0;
								if ($role =='Accountant' || $role =='Checker - Account' || $role =='HOD - Account' || $role =='Checker' || $role =='Maker'  ){
									$sql="SELECT approval_status, count(*) as cnt from sma_supplier_invoice where company_id in ( $comid ) and  approval_status in('Rejected') 
										and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'SI') 
										or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'SI') or draft_by = '$user' ) group by approval_status ";
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_supplier_invoice` where company_id in ($comid) and approval_status = 'Rejected' and draft_by = '$user' group by approval_status";
								}
								//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_supplier_invoice` where approval_status = 'Rejected' and draft_by = '$user'  group by approval_status";
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$rcnt = $r1['cnt'];
								}
							?>		
		
		  <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab" id="first_tab" > Pending <span class="btn btn-info" ><?php echo $pcnt; ?></span></a></li>
              <li><a href="#tab_2" data-toggle="tab" id="second_tab">Approved <span class="btn btn-success" ><?php echo $acnt; ?></span></a></li>
              <li><a href="#tab_3" data-toggle="tab" id="third_tab">Rejected <span class="btn btn-danger" ><?php echo $rcnt; ?></span></a></li>
			  <h3 style="text-align:right;">Supplier Invoice</h3>
		</ul>
		  
			<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
		<?php
			//$sql="SELECT * from sma_supplier_invoice where  approval_status in('Pending') and company in ( $comid ) and draft_by = '$user' ";
			if ($role =='Accountant' || $role =='Checker - Account' || $role =='HOD - Account' || $role =='Checker' || $role =='Maker'  ){
				$sql="SELECT * from sma_supplier_invoice where company_id in ( $comid ) and  approval_status in('Pending', 'Verified')  
				and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'SI') 
				or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'SI') or draft_by = '$user' )  order by id desc ";
			}
			else {
				$sql = "SELECT * FROM `sma_supplier_invoice` where company_id in ($comid) and approval_status in('Pending', 'Verified')  and draft_by = '$user' order by id desc ";
			}
			
//echo $role. ' ' . $sql;
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
              <table id="prtable1" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>#</th>				
					<th>Dated</th>
					<th>Supp.Inv.No.</th>
					<th>Company Name</th>
					<th>Supplier Name</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
					
					<th style="text-align:right;">Action</th>
				
				</tr>
                </thead>
                <tbody>
			<?php
					while($row = mysqli_fetch_array($result)){
						
						$company = $row['company_id'];
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$company_name  = $r2['comp_name'];

						$to_supplier = $row['suplier_name'];
						$sql 	= "select * from sma_party_mst where id = '$to_supplier' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$suplier_name = $r2['party_name'];
	
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
					<td width="5%"><?php echo $row['id'];?></td>
					<td width="10%"><?php echo date('d-m-Y', strtotime($row['invoice_date']));?></td>
					<td width="10%"><?php echo $row['supplier_invoice_no'];?></td>
					<td width="15%"><?php echo $company_name;?></td>
					<td width="15%"><?php echo $suplier_name;?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
					<td width="10%"><?php echo $row['status'];?></td>
					<td width="10%"><?php echo $row['approval_status'];?></td>
					
					<td width="5%" style="text-align:right;" ><a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>"><i class="fa fa-edit"></i></a> </td>
		
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
			 
					
<!---------------------------------------------------------------------------------------------------------------------------------------------------------------->
						
	        

	<?php						
		$comid  = $_SESSION['comid'];
		$role	= $_SESSION['role'];
		$user_category	= $_SESSION['user_category'];
		$sql = "SELECT * from sma_workflow where doc_type= 'PR' and user_category = '$user_category' ";
					$result = mysqli_query($con, $sql);
					$row = mysqli_fetch_array($result);
					$from_value 		= $row['from_value'];
					$to_value 			= $row['to_value'];
					$project_manager	= $row['project_manager'];
					$project_incharge 	= $row['project_incharge'];
					$coo_cxo 			= $row['coo_cxo'];
					
		//echo $role."<<<>>>";			
		$rowcount = 0;
		
			//$sql="SELECT * from sma_supplier_invoice where  approval_status in('Approved') and company in ( $comid ) and draft_by = '$user' ";
			if ($role =='Accountant' || $role =='Checker - Account' || $role =='HOD - Account' || $role =='Checker'  || $role =='Maker' ){
				$sql="SELECT * from sma_supplier_invoice where company_id in ( $comid ) and  approval_status in('Approved') 
				and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'SI') 
				or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'SI') or draft_by = '$user' )  order by id desc ";
			}
			else {
				$sql = "SELECT * FROM `sma_supplier_invoice` where company_id in ($comid) and approval_status = 'Approved' and draft_by = '$user' order by id desc";
			}
//echo $sql;
		
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		
			$modulePath1 = "supp_invoice/";
	?>


		  <!-- Step 1 -->
            <div class="tab-pane" id="tab_2">

            <!-- /.box-header -->
     
			   
            <!-- /.box-header -->
              <table id="prtable1" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>#</th>				
					<th>Dated</th>
					<th>Supp.Inv.No.</th>
					<th>Company Name</th>
					<th>Supplier Name</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
					
					<th style="text-align:right;">Action</th>
				
				</tr>
                </thead>
                <tbody>
			<?php
					while($row = mysqli_fetch_array($result)){
						
						$company = $row['company_id'];
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company_name  = $r2['comp_name'];
						
						$to_supplier = $row['suplier_name'];
						$sql 	= "select * from sma_party_mst where id = '$to_supplier' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$suplier_name = $r2['party_name'];
	
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $j;?>" > </td>
					<td width="5%"><?php echo $row['id'];?> </td>
					<td width="10%"><?php echo date('d-m-Y', strtotime($row['invoice_date']));?></td>
					<td width="10%"><?php echo $row['supplier_invoice_no'];?></td>
					<td width="15%"><?php echo $company_name;?></td>
					<td width="15%"><?php echo $suplier_name;?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
					<td width="10%"><?php echo $row['status'];?></td>
					<td width="10%"><?php echo $row['approval_status'];?></td>
					
					<td width="5%" style="text-align:right;" ><a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>"><i class="fa fa-edit"></i></a> </td>
		
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
          <!-- /.box -->
	
	<?php						
		$comid  = $_SESSION['comid'];
		$role	= $_SESSION['role'];
		$user_category	= $_SESSION['user_category'];
		$sql = "SELECT * from sma_workflow where doc_type= 'PR' and user_category = '$user_category' ";
					$result = mysqli_query($con, $sql);
					$row = mysqli_fetch_array($result);
					$from_value 		= $row['from_value'];
					$to_value 			= $row['to_value'];
					$project_manager	= $row['project_manager'];
					$project_incharge 	= $row['project_incharge'];
					$coo_cxo 			= $row['coo_cxo'];
					
		//echo $role."<<<>>>";			
		$rowcount = 0;
		
//			$sql="SELECT * from sma_supplier_invoice where  approval_status in('Rejected') and company in ( $comid ) and draft_by = '$user' ";
			if ($role =='Accountant' || $role =='Checker - Account' || $role =='HOD - Account' || $role =='Checker'  || $role =='Maker' ){
				$sql="SELECT * from sma_supplier_invoice where company_id in ( $comid ) and  approval_status in('Rejected') 
				and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'SI') 
				or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'SI') or draft_by = '$user' )  order by id desc ";
			}
			else {
				$sql = "SELECT * FROM `sma_supplier_invoice` where company_id in ($comid) and approval_status = 'Rejected' and draft_by = '$user' order by id desc ";
			}		
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);

			$modulePath1 = "supp_invoice/";
	?>

           <div class="tab-pane" id="tab_3">
								
            <!-- /.box-header -->
            <div class="box-body">
              <table id="prtable1" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>#</th>
					<th>Dated</th>
					<th>Supp.Inv.No.</th>
					<th>Company Name</th>
					<th>Supplier Name</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
					
					<th style="text-align:right;">Action</th>
				
				</tr>
                </thead>
                <tbody>
			<?php
					while($row = mysqli_fetch_array($result)){
						
						$to_supplier = $row['suplier_name'];
						$sql 	= "select * from sma_party_mst where id = '$to_supplier' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$suplier_name = $r2['party_name'];
	
						$company = $row['company_id'];
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company_name  = $r2['comp_name'];

						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $j;?>" > </td>
					<td width="5%"><?php echo $row['id'];?> </td>
					<td width="10%"><?php echo date('d-m-Y', strtotime($row['invoice_date']));?></td>
					<td width="10%"><?php echo $row['supplier_invoice_no'];?></td>
					<td width="15%"><?php echo $company_name;?></td>
					<td width="15%"><?php echo $suplier_name;?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
					<td width="10%"><?php echo $row['status'];?></td>
					<td width="10%"><?php echo $row['approval_status'];?></td>
					
					<td width="5%" style="text-align:right;" ><a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>"><i class="fa fa-edit"></i></a> </td>
		
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		</div>				
       </div>
	</div>
  </div>
</div>
</div>

  
          <!-- /.box -->
        
<!----IPC DASHBOARD-->

<div class="row">
      <div class="col-xs-12">
          <div class="box">
		  
		  <div class="box-body">
		  
							<?php
								$pcnt = 0;
								if ( $role =='Accountant'){
									$sql="SELECT approval_status, count(*) as cnt from sma_ipc where sma_comp_id in ( $comid ) and  approval_status in('Pending', 'Prepared') 
										and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'IP') 
										or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'IP') or draft_by = '$user' ) group by approval_status ";
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_ipc` where sma_comp_id in ($comid) and approval_status in('Pending', 'Prepared')  and draft_by = '$user' group by approval_status";
								}
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt = $r1['cnt'];
								}
									
								$acnt = 0;
								if ($role =='Accountant'){
									$sql="SELECT approval_status, count(*) as cnt from sma_ipc where sma_comp_id in ( $comid ) and  approval_status in('Approved') 
										and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'IP') 
										or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'IP') or draft_by = '$user' )  group by approval_status ";
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_ipc` where sma_comp_id in ($comid) and approval_status = 'Approved' and draft_by = '$user' group by approval_status";
								}
								//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_ipc` where approval_status = 'Approved'  and draft_by = '$user'  group by approval_status";
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$acnt = $r1['cnt'];
								}
								
								$rcnt = 0;
								if ($role =='Accountant'){
									$sql="SELECT approval_status, count(*) as cnt from sma_ipc where sma_comp_id in ( $comid ) and  approval_status in('Rejected') 
										and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'IP') 
										or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'IP') or draft_by = '$user' ) group by approval_status ";
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_ipc` where sma_comp_id in ($comid) and approval_status = 'Rejected' and draft_by = '$user' group by approval_status";
								}
								//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_ipc` where approval_status = 'Rejected' and draft_by = '$user'  group by approval_status";
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$rcnt = $r1['cnt'];
								}
							?>		
		
		  <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1IPC" data-toggle="tab" id="first_tab" > Pending <span class="btn btn-info" ><?php echo $pcnt; ?></span></a></li>
              <li><a href="#tab_2IPC" data-toggle="tab" id="second_tab">Approved <span class="btn btn-success" ><?php echo $acnt; ?></span></a></li>
              <li><a href="#tab_3IPC" data-toggle="tab" id="third_tab">Rejected <span class="btn btn-danger" ><?php echo $rcnt; ?></span></a></li>
			  <h3 style="text-align:right;">IPC</h3>
		</ul>
		  
			<div class="tab-content">
				<div class="tab-pane active" id="tab_1IPC">
						
            <!-- /.box-header -->
		<?php
			
			$modulePathi = "ipc/";
		
			//$sql="SELECT * from sma_ipc where  approval_status in('Pending') and company in ( $comid ) and draft_by = '$user' ";
			if ($role =='Accountant'){
				$sql="SELECT * from sma_ipc where sma_comp_id in ( $comid ) and  approval_status in('Pending', 'Prepared')  
				and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'IP') 
				or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'IP') or draft_by = '$user' )  order by id desc ";
			}
			else {
				$sql = "SELECT * FROM `sma_ipc` where sma_comp_id in ($comid) and approval_status in('Pending', 'Prepared')  and draft_by = '$user' order by id desc ";
			}
			
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
              <table id="prtable1" class="table table-bordered table-striped">
                <thead>
                <tr>
 					<th>Company</th>
					<th>Party</th>
					<th>PO.Number</th>
					<th>Invoice No.</th>
					<th>PO Amount</th>
					<th>Invoice Amount</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
				
				</tr>
                </thead>
                <tbody>
			<?php
					while($row = mysqli_fetch_array($result)){						

						$sma_vendor_id = $row['sma_vendor_id'];
						$sql = "select * from sma_party_mst where id = '$sma_vendor_id' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$sma_vendor_name = $r2['party_name'];
						
						$sma_comp_id = $row['sma_comp_id'];
						$sql = "select * from company where comp_id = '$sma_comp_id' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$comp_name = $r2['comp_name'];
						
						$sma_po_no = $row['sma_po_no'];
						$sql = "select * from sma_purchase_order where id = '$sma_po_no' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$po_number = $r2['po_number'];
						
						$sma_invoice_no = $row['sma_invoice_no'];
						$sql = "select * from sma_supplier_invoice where id = '$sma_invoice_no' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$sma_invoice_no = $r2['supplier_invoice_no'];
						
						$baseurl1 = $baseurl.$modulePath.'ipc.php?sub=edit&id='.$row["id"];	
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePathi.'ipc.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePathi . "ipc.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="20%"><?php echo $comp_name;?></td>
					<td width="20%"><?php echo $sma_vendor_name;?></td>
					<td width="10%"><?php echo $po_number;?></td>
					<td width="10%"><?php echo $sma_invoice_no;?></td>
					<td width="10%"><?php echo $row['sma_po_amount'];?></td>
					<td width="10%"><?php echo $row['sma_invoice_amount'];?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $row['approval_status'];?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
					
			</tr>
		</a>
				<?php } ?>
				
                </tbody>
              </table>
            </div>
		
			 
					
<!---------------------------------------------------------------------------------------------------------------------------------------------------------------->
						
	        

	<?php						
		$comid  = $_SESSION['comid'];
		$role	= $_SESSION['role'];
		$user_category	= $_SESSION['user_category'];
		$sql = "SELECT * from sma_workflow where doc_type= 'PR' and user_category = '$user_category' ";
					$result = mysqli_query($con, $sql);
					$row = mysqli_fetch_array($result);
					$from_value 		= $row['from_value'];
					$to_value 			= $row['to_value'];
					$project_manager	= $row['project_manager'];
					$project_incharge 	= $row['project_incharge'];
					$coo_cxo 			= $row['coo_cxo'];
					
		//echo $role."<<<>>>";			
		$rowcount = 0;
		
			//$sql="SELECT * from sma_ipc where  approval_status in('Approved') and company in ( $comid ) and draft_by = '$user' ";
			if ($role =='Accountant'){
				$sql="SELECT * from sma_ipc where sma_comp_id in ( $comid ) and  approval_status in('Approved') 
				and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'IP') 
				or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'IP') or draft_by = '$user' )  order by id desc ";
			}
			else {
				$sql = "SELECT * FROM `sma_ipc` where sma_comp_id in ($comid) and approval_status = 'Approved' and draft_by = '$user' order by id desc";
			}
//echo $sql;
		
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		
			$modulePathi = "ipc/";
	?>


		  <!-- Step 1 -->
            <div class="tab-pane" id="tab_2IPC">

            <!-- /.box-header -->
     
			   
            <!-- /.box-header -->
              <table id="prtable1" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>Company</th>
					<th>Party</th>
					<th>PO.Number</th>
					<th>Invoice No.</th>
					<th>PO Amount</th>
					<th>Invoice Amount</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
					
				</tr>
                </thead>
                <tbody>
			<?php
					while($row = mysqli_fetch_array($result)){
						

						$sma_vendor_id = $row['sma_vendor_id'];
						$sql = "select * from sma_party_mst where id = '$sma_vendor_id' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$sma_vendor_name = $r2['party_name'];
						
						$sma_comp_id = $row['sma_comp_id'];
						$sql = "select * from company where comp_id = '$sma_comp_id' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$comp_name = $r2['comp_name'];
						
						$sma_po_no = $row['sma_po_no'];
						$sql = "select * from sma_purchase_order where id = '$sma_po_no' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$po_number = $r2['po_number'];
						
						$sma_invoice_no = $row['sma_invoice_no'];
						$sql = "select * from sma_supplier_invoice where id = '$sma_invoice_no' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$sma_invoice_no = $r2['supplier_invoice_no'];
						
						$baseurl1 = $baseurl.$modulePath.'ipc.php?sub=edit&id='.$row["id"];	
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePathi.'ipc.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
						<td width="20%"><?php echo $comp_name;?></td>
						<td width="20%"><?php echo $sma_vendor_name;?></td>
						<td width="10%"><?php echo $po_number;?></td>
						<td width="10%"><?php echo $sma_invoice_no;?></td>
						<td width="10%"><?php echo $row['sma_po_amount'];?></td>
						<td width="10%"><?php echo $row['sma_invoice_amount'];?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $row['approval_status'];?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
				
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
          <!-- /.box -->
	
	<?php						
		$comid  = $_SESSION['comid'];
		$role	= $_SESSION['role'];
		$user_category	= $_SESSION['user_category'];
		$sql = "SELECT * from sma_workflow where doc_type= 'PR' and user_category = '$user_category' ";
					$result = mysqli_query($con, $sql);
					$row = mysqli_fetch_array($result);
					$from_value 		= $row['from_value'];
					$to_value 			= $row['to_value'];
					$project_manager	= $row['project_manager'];
					$project_incharge 	= $row['project_incharge'];
					$coo_cxo 			= $row['coo_cxo'];
					
		//echo $role."<<<>>>";			
		$rowcount = 0;
		
//			$sql="SELECT * from sma_ipc where  approval_status in('Rejected') and company in ( $comid ) and draft_by = '$user' ";
			if ($role =='Accountant'){
				$sql="SELECT * from sma_ipc where sma_comp_id in ( $comid ) and  approval_status in('Rejected') 
				and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'IP') 
				or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'IP') or draft_by = '$user' )  order by id desc ";
			}
			else {
				$sql = "SELECT * FROM `sma_ipc` where sma_comp_id in ($comid) and approval_status = 'Rejected' and draft_by = '$user' order by id desc ";
			}		
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);

			$modulePathi = "ipc/";
	?>

           <div class="tab-pane" id="tab_3IPC">
								
            <!-- /.box-header -->
            <div class="box-body">
              <table id="prtable1" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>Company</th>
					<th>Party</th>
					<th>PO.Number</th>
					<th>Invoice No.</th>
					<th>PO Amount</th>
					<th>Invoice Amount</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
				
				</tr>
                </thead>
                <tbody>
			<?php
					while($row = mysqli_fetch_array($result)){
						
										
						$sma_vendor_id = $row['sma_vendor_id'];
						$sql = "select * from sma_party_mst where id = '$sma_vendor_id' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$sma_vendor_name = $r2['party_name'];
						
						$sma_comp_id = $row['sma_comp_id'];
						$sql = "select * from company where comp_id = '$sma_comp_id' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$comp_name = $r2['comp_name'];
						
						$sma_po_no = $row['sma_po_no'];
						$sql = "select * from sma_purchase_order where id = '$sma_po_no' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$po_number = $r2['po_number'];
						
						$sma_invoice_no = $row['sma_invoice_no'];
						$sql = "select * from sma_supplier_invoice where id = '$sma_invoice_no' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$sma_invoice_no = $r2['supplier_invoice_no'];
						
						$baseurl1 = $baseurl.$modulePath.'ipc.php?sub=edit&id='.$row["id"];
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePathi.'ipc.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="20%"><?php echo $comp_name;?></td>
						<td width="20%"><?php echo $sma_vendor_name;?></td>
						<td width="10%"><?php echo $po_number;?></td>
						<td width="10%"><?php echo $sma_invoice_no;?></td>
						<td width="10%"><?php echo $row['sma_po_amount'];?></td>
						<td width="10%"><?php echo $row['sma_invoice_amount'];?></td>
						<td width="10%"><?php echo $row['changed_by'];?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $row['approval_status'];?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
				
			</tr>
		</a>
				<?php } ?>
				
                </tbody>
              </table>
            </div>
		</div>				
       </div>
	</div>
  </div>
</div>
</div>

<!-- IPC DASHBOARD -->


    <?php 
	
		$rowcount =0;	
//		if ($rowcount > 0){
			$modulePath2 = "payment/";
	?>
	
<div class="row">
    <div class="col-xs-12">
        <div class="box">
		  
		  <div class="box-body">
		  
								<?php
								$pcnt = 0;
								if ($role =='Accountant' || $role =='Checker - Account' || $role =='HOD - Account' || $role =='Checker'  || $role =='Maker' ){
									$sql="SELECT approval_status, count(*) as cnt from payment_header where company_id in ( $comid ) and  approval_status in('Pending', 'Verified')  
										and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'PY') 
										or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PY') or draft_by = '$user' ) group by approval_status ";
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `payment_header` where company_id in ($comid) and approval_status in('Pending', 'Verified')  and draft_by = '$user' group by approval_status";
								}
//echo $sql."<BR>";
								//$sql = "SELECT approval_status, count(*) as cnt FROM `payment_header` where approval_status = 'Pending' group by approval_status";
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt = $r1['cnt'];
								}
								?> 
								<?php	
								$acnt = 0;
								if ($role =='Accountant' || $role =='Checker - Account' || $role =='HOD - Account' || $role =='Checker'  || $role =='Maker' ){
									$sql="SELECT approval_status, count(*) as cnt from payment_header where company_id in ( $comid ) and  approval_status in('Approved') 
										and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'PY') 
										or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PY') or draft_by = '$user' )  group by approval_status ";
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `payment_header` where company_id in ($comid) and approval_status = 'Approved'  and draft_by = '$user' group by approval_status";
								}
								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$acnt = $r1['cnt'];
								}
									?>
								
								<?php
								$rcnt = 0;
								
								if ($role =='Accountant' || $role =='Checker - Account' || $role =='HOD - Account' || $role =='Checker'  || $role =='Maker' ){
									$sql="SELECT approval_status, count(*) as cnt from payment_header where company_id in ( $comid ) and  approval_status in('Rejected') 
										and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'PY')
										or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PY') or draft_by = '$user' )  group by approval_status ";
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `payment_header` where company_id in ( $comid ) and approval_status = 'Rejected'  and draft_by = '$user' group by approval_status";
								}
			
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$rcnt = $r1['cnt'];
								}
									?> 

		  <!-- Step 1 -->

		<ul class="nav nav-tabs">
              <li class="active"><a href="#tab_11" data-toggle="tab" id="first_tab" > Pending <span class="btn btn-info" ><?php echo $pcnt; ?></span></a></li>
              <li><a href="#tab_22" data-toggle="tab" id="second_tab">Approved <span class="btn btn-success" ><?php echo $acnt; ?></span></a></li>
              <li><a href="#tab_33" data-toggle="tab" id="third_tab">Rejected <span class="btn btn-danger" ><?php echo $rcnt; ?></span></a></li>
			  <h3 style="text-align:right;">Payment</h3>
		</ul>
		  
		  <div class="tab-content">
			   <div class="tab-pane active" id="tab_11">
    
            <!-- /.box-header -->
            <div class="box-body">
              <table id="prtable1" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>Paid Date</th>
					<th>Paid via</th>
					<th>Cheque Number</th>
					<th>Dated.</th>
					<th style="text-align:right;">Amount Paid</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
					
					<th style="text-align:right;">Action</th>
				
				</tr>
                </thead>
                <tbody>
	<?php
		if ($role =='Accountant' || $role =='Checker - Account' || $role =='HOD - Account'  || $role =='Checker'  || $role =='Maker' ){
			$sql="SELECT * from payment_header where company_id in ( $comid ) and  approval_status in('Pending', 'Verified')  
				and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'PY') 
				or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PY') or draft_by = '$user' )  order by id desc ";
		}
		else {
			$sql = "SELECT * FROM `payment_header` where company_id in ($comid) and approval_status in('Pending', 'Verified')  and draft_by = '$user' order by id desc ";
		}
								
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$rowcount=mysqli_num_rows($result);
	
	while($row = mysqli_fetch_array($result)){

		$cash_bank_name = $row['cash_bank_name'];
		$sql 	= "select * from account_mst where id = '$cash_bank_name' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$cash_bank_name = $r2['account_name'];
		
		$dated = date('d-m-Y', strtotime($row['dated']));
		if($dated =='01-01-1970'){
			$dated = '';
		}
	
		$deduction_amt		= $row["tds_amount"];
		$total_amount_paid	= $row['total_amount_paid'];
		$actual_paid		= $total_amount_paid ;

		$baseurl1 = $baseurl.$modulePath2.'edit.php?sub=edit&id='.$row["id"];						
		
		?>
		
	<a href="<?php echo $baseurl1;?>" title="Edit">
	<tr style="cursor:pointer; "onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
		<td width="10%"><?php echo date('d-m-Y', strtotime($row['paid_date']));?></td>
		<td width="10%"><?php echo $cash_bank_name;?></td>
		<td width="10%"><?php echo $row['cheque_no'];?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%" style="text-align:right;"><?php echo number_format($actual_paid,2);?></td>
		<td width="08%"><?php echo $row['draft_by'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		
		<td width="5%" style="text-align:right;">
		<a href="<?php echo $baseurl . $modulePath2;?>edit.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		<!--<a href="edit.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		</td>
    </tr>
	</a>
		<?php } ?>
				
                
                </tbody>
                <tfoot>
                
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
		</div>	
          
	
	<?php
	$rowcount =0;	
		$modulePath2 = "payment/";
	?>
	
	<div class="tab-pane" id="tab_22">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="prtable1" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>Paid Date</th>
					<th>Paid via</th>
					<th>Cheque Number</th>
					<th>Dated.</th>
					<th style="text-align:right;">Amount Paid</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
					
					<th style="text-align:right;">Action</th>
				
				</tr>
                </thead>
                <tbody>
	<?php
		if ($role =='Accountant' || $role =='Checker - Account' || $role =='HOD - Account'  || $role =='Checker'  || $role =='Maker' ){
			$sql="SELECT * from payment_header where company_id in ( $comid ) and  approval_status in('Approved') 
				and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'PY') 
				or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PY') or draft_by = '$user' )  order by id desc ";
		}
		else {
			$sql = "SELECT * FROM `payment_header` where company_id in ($comid) and approval_status = 'Approved' and draft_by = '$user' order by id desc ";
		}
		
	//	$sql="SELECT * from payment_header where company_id in ($comid) and approval_status in('Approved') and draft_by = '$user' order by id desc ";
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$rowcount=mysqli_num_rows($result);
//echo $sql."<BR>";		
		
	while($row = mysqli_fetch_array($result)){
		
		$cash_bank_name = $row['cash_bank_name'];
		$sql 	= "select * from account_mst where id = '$cash_bank_name' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$cash_bank_name = $r2['account_name'];
		
		$dated = date('d-m-Y', strtotime($row['dated']));
		if($dated =='01-01-1970'){
			$dated = '';
		}
		
		$deduction_amt		= $row["tds_amount"];
		$total_amount_paid	= $row['total_amount_paid'];
		$actual_paid		= $total_amount_paid - $deduction_amt;
				
		$baseurl1 = $baseurl.$modulePath2.'edit.php?sub=edit&id='.$row["id"];						
		
		?>
		
	<a href="<?php echo $baseurl1;?>" title="Edit">
	<tr style="cursor:pointer; "onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
		<td width="10%"><?php echo date('d-m-Y', strtotime($row['paid_date']));?></td>
		<td width="10%"><?php echo $cash_bank_name;?></td>
		<td width="10%"><?php echo $row['cheque_no'];?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%" style="text-align:right;"><?php echo number_format($actual_paid,2);?></td>
		<td width="08%"><?php echo $row['draft_by'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		
		<td width="5%" style="text-align:right;">
		<a href="<?php echo $baseurl . $modulePath2;?>edit.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		<!--<a href="edit.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		</td>
    </tr>
	</a>
				<?php } ?>
				
                
                </tbody>
                <tfoot>
                
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
		 
	<?php
	$rowcount =0;	
		$modulePath2 = "payment/";
	?>
		<div class="tab-pane" id="tab_33">
		
            <!-- /.box-header -->
            <div class="box-body">
              <table id="prtable1" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>Paid Date</th>
					<th>Paid via</th>
					<th>Cheque Number</th>
					<th>Dated.</th>
					<th style="text-align:right;">Amount Paid</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
					
					<th style="text-align:right;">Action</th>
				
				</tr>
                </thead>
                <tbody>
	<?php
		if ($role =='Accountant' || $role =='Checker - Account' || $role =='HOD - Account' || $role =='Checker'  || $role =='Maker' ){
			$sql="SELECT * from payment_header where company_id in ( $comid ) and  approval_status in('Rejected') 
				and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'PO') 
				or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PO') or draft_by = '$user' )  order by id desc ";
		}
		else {
			$sql = "SELECT * FROM `payment_header` where company_id in ($comid) and approval_status = 'Rejected' and draft_by = '$user' order by id desc ";
		}
		
//$sql="SELECT * from payment_header where company_id in ($comid) and approval_status in('Rejected') and draft_by = '$user' order by id desc ";
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$rowcount=mysqli_num_rows($result);
//echo $sql."<br>";				
	while($row = mysqli_fetch_array($result)){
		
		$cash_bank_name = $row['cash_bank_name'];
		$sql 	= "select * from account_mst where id = '$cash_bank_name' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$cash_bank_name = $r2['account_name'];
		
		$dated = date('d-m-Y', strtotime($row['dated']));
		if($dated =='01-01-1970'){
			$dated = '';
		}
		
		$deduction_amt		= $row["tds_amount"];
		$total_amount_paid	= $row['total_amount_paid'];
		$actual_paid		= $total_amount_paid - $deduction_amt;
				
		$baseurl1 = $baseurl.$modulePath2.'edit.php?sub=edit&id='.$row["id"];						
		
		?>
		
	<a href="<?php echo $baseurl1;?>" title="Edit">
	<tr style="cursor:pointer; "onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
		<td width="10%"><?php echo date('d-m-Y', strtotime($row['paid_date']));?></td>
		<td width="10%"><?php echo $cash_bank_name;?></td>
		<td width="10%"><?php echo $row['cheque_no'];?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%" style="text-align:right;"><?php echo number_format($actual_paid,2);?></td>
		<td width="08%"><?php echo $row['draft_by'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		
		<td width="5%" style="text-align:right;">
		<a href="<?php echo $baseurl . $modulePath2;?>edit.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		<!--<a href="edit.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		</td>
    </tr>
	</a>
				<?php } ?>
				
                
                </tbody>
                <tfoot>
                
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
		 </div>
	     </div>
		</div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>

	
	  </section>
    <!-- /.content -->
  </div>

  <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->
<?php
//include("footer.php");
?>

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(function () {
        $("#prtable").DataTable();
    });
</script>

</body>
</html>

