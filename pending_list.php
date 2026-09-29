<?php
include("header.php");
$modulePath = "/";
?>
<!-- DataTables -->
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Status <small>List</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Status List </li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
						
			<?php
			
				$targetpage = "pending_list.php?sub=list"; 
				$limit = 10; 
				$start = 0;	
				
				if(empty($module)){
					$module='A'; 
					$_SESSION['module'] = $module;
				}
				
				if ($_POST['module'] ){
					$_SESSION['module'] = $_POST['module'];
				}
				
				if ( $_SESSION['module'] ){
					$module 		= $_SESSION['module'];
					$_SESSION['reset']='';
				}
				
				if ( !empty($_GET['reset']) || !empty($_SESSION['reset']) ){
					$_SESSION['module'] = '';
					$module = $_SESSION['module'];
				}
				
			?>
			
					<form class="form-horizontal" action="pending_list.php?sub=list" method="post">
                      
						<div class="form-group">
								
								<label for="reqDate" class="col-lg-1 control-label">Module</label>
								<div class="col-md-2">
                                    <select class="form-control select2" name="module" id="module" >
										<option value=""> Select </option>
										<option value="A" <?php echo ($module == 'A')?'selected="selected"':'';?> > Approval Memo </option>
										<option value="B" <?php echo ($module == 'B')?'selected="selected"':'';?>> Purchase Order </option>
										<option value="C" <?php echo ($module == 'C')?'selected="selected"':'';?>> GRN SRN </option>
										<option value="D" <?php echo ($module == 'D')?'selected="selected"':'';?>> Supplier Invoice </option>
										<option value="E" <?php echo ($module == 'E')?'selected="selected"':'';?>> IPC </option>
										<option value="F" <?php echo ($module == 'F')?'selected="selected"':'';?>> Payment </option>
										<option value="G" <?php echo ($module == 'G')?'selected="selected"':'';?>> Travel Request </option>
										<option value="H" <?php echo ($module == 'H')?'selected="selected"':'';?>> Travel Expenses </option>
										<option value="I" <?php echo ($module == 'I')?'selected="selected"':'';?>> Regular Expenses </option>
										<option value="J" <?php echo ($module == 'J')?'selected="selected"':'';?>> Operating Expenses </option>
									</select>
								</div>

							<div class="col-xs-4">
                                		
							<input class="btn btn-primary" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
							<a href="pending_list.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>
							
						</div>
							
				<span class="pull-right"><a href="<?php echo $baseurl . $modulePath . "pending_trans_repo.php?sub=pdf"?>" class="btn btn-primary" target="_blank">Report</a>&nbsp;&nbsp;&nbsp;&nbsp; </span>
				<!--<span class="pull-right"><a href="<?php echo $baseurl . $modulePath . "pending_trans_mail.php?sub=pdf&send=mail"?>" class="btn btn-primary" target="_blank">Mail</a>&nbsp;&nbsp;&nbsp;&nbsp; </span>-->
				
				<span class="pull-right"><a href="#checkerAuthority" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#checkerAuthority">Send Mail</a>&nbsp;&nbsp;&nbsp;&nbsp; </span>
				
			
						</div>
						
				</form>

			<!--<span id="predit"> </span>-->
		
            <!-- /.box-header -->
            <div class="box-body">
              <table id="prtable123" class="table table-bordered table-striped">
                <thead>
                <tr>
				<?php 
				if($module=='A' || $module=='B'){ 
					$hdg_a 	= "Company";
					$hdg_b	= "Amount";
				} 
				else if($module=='C'){
					$hdg_a = "Supplier Invoice No.";
					$hdg_b	= "PO.REF.No.";
				 }
				 else if( $module =='D' || $module =='E'){
					$hdg_a	= "PO.REF.No.";
					$hdg_b	= "Amount";
				 }
				 else if($module=='F'){
					$hdg_a = "Paid Via";
					$hdg_b	= "Amount";
					$hdg_c	= "Supp.No.";
				 }
				 
				?>
				
				<?php if($module!='G' && $module!='H' && $module!='I' && $module!='J'){ ?>
                    <td style='width: 5%;'><b>No.</b></td>
					<td style='width: 09%;'><b>Date</b> </td>
					
					<td style='width: 21%;'><b><?php echo $hdg_a ?></b></td>
					<td style='width: 22%;'><b>Supplier Name</b></td>
					<?php if($module=='F'){ ?>
					<td><b> <?php echo $hdg_c ?></b></td>
					<?php } ?>
					<td style='width: 10%;text-align: right;' ><b><?php echo $hdg_b ?></b></td>
				<?php } 
					else if($module=='G'){ ?>	
						<th>SrNo.</th>
						<th>Name</th>
						<th>Location From</th>
						<th>Date From</th>
						<th>Location To</th>
						<th>Date To</th>
						<th>Company</th>
						<th>Advance Amount</th>
			<?php }
					else if($module=='H'){ ?>
						<th>SrNo.</th>
						<th>Name</th>
						<th>Company</th>
						<th>Date</th>
						<th>Approval Ref.no.</th>
						<th style="text-align:right;">Trip.Amount</th>
						<th style="text-align:right;">Exp.Amount</th>
				<?php } else if($module=='I' || $module=='J'){ ?>
						<th>SrNo.</th>
						<th>Name</th>
						<th>Company</th>
						<th>Date</th>
						<th  style="text-align:right;">Total Amount</th>
				<?php } ?>
				
				
					<td style='width: 10%;'><b>Sent By</b></td>
					<td style='width: 8%;'><b>Send Date</b></td>
					<td style='width: 10%;'><b>Pending To</b></td>
					<td style='width: 4%;'><b>Pending Days</b></td>
				
				</tr>
                </thead>
                <tbody>
				<?php
					//$sql="SELECT * from sma_approval_memo order by id desc";
					
					$role			= $_SESSION['role'];
					$user_category	= $_SESSION['user_category'];
					
					if(empty($module)){$module ='A';}
					
					if($module=='A' ){
						$id_var = '';
						$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = 'AP'  group by doc_id";
						$qry = mysqli_query($con,$sql);
						while($rs = mysqli_fetch_array($qry)){
							$id_var .= $rs['idd'].',';
						
						}
						$id_var .= '0';
						
						$sql = "SELECT  DS.*, DS1.create_by, DS1.reviewed_by, DS1.remarks, DS1.create_date from sma_approval_memo DS INNER JOIN (SELECT * FROM 	`workflow_history` where doc_type = 'AP' and status in('Pending', 'Verified','Prepared') 
							and id in ($id_var) ) DS1 
							ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Prepared') order by reviewed_by, create_by ";
						
						$query = "SELECT  count(*) as num from sma_approval_memo DS INNER JOIN (SELECT * FROM 	`workflow_history` where doc_type = 'AP' and status in('Pending', 'Verified','Prepared') 
							and id in ($id_var) ) DS1 
							ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Prepared') ";	
						//echo $sql;	
					}						
					else if($module=='B'){
						$id_var = '';
						$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = 'PO'  group by doc_id";
						$qry = mysqli_query($con,$sql);
						while($rs = mysqli_fetch_array($qry)){
							$id_var .= $rs['idd'].',';
						
						}
						$id_var .= '0';
						$sql = "SELECT  DS.*, DS1.create_by, DS1.reviewed_by, DS1.remarks, DS1.create_date from sma_purchase_order DS INNER JOIN (SELECT * FROM 	`workflow_history` where doc_type = 'PO' and status in('Pending', 'Verified','Prepared') 
							and id in ($id_var) ) DS1 
							ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Prepared') order by reviewed_by, create_by ";
						
						$query = "SELECT  count(*) as num from sma_purchase_order DS INNER JOIN (SELECT * FROM 	`workflow_history` where doc_type = 'PO' and status in('Pending', 'Verified','Prepared') 
							and id in ($id_var) ) DS1 
							ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Prepared') ";
					}
					else if($module=='C'){  // GRN SRN
						$id_var = '';
						$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = 'GS'  group by doc_id";
						$qry = mysqli_query($con,$sql);
						while($rs = mysqli_fetch_array($qry)){
							$id_var .= $rs['idd'].',';
						
						}
						$id_var .= '0';
						$sql = "SELECT  DS.*, DS1.create_by, DS1.reviewed_by, DS1.remarks, DS1.create_date from sma_grn_srn DS INNER JOIN (SELECT * FROM 	`workflow_history` where doc_type = 'GS' and status in('Pending', 'Verified','Prepared') 
							and id in ($id_var) ) DS1 
							ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Prepared') order by reviewed_by, create_by ";
					
						$query = "SELECT  count(*) as num from sma_grn_srn DS INNER JOIN (SELECT * FROM 	`workflow_history` where doc_type = 'GS' and status in('Pending', 'Verified','Prepared') 
							and id in ($id_var) ) DS1 
							ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Prepared') ";
					}
					else if($module=='D'){ //Supplier Invoice
						$id_var = '';
						$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = 'SI'  group by doc_id";
						$qry = mysqli_query($con,$sql);
						while($rs = mysqli_fetch_array($qry)){
							$id_var .= $rs['idd'].',';
						
						}
						$id_var .= '0';
						$sql = "SELECT  DS.*, DS1.create_by, DS1.reviewed_by, DS1.remarks, DS1.create_date from sma_supplier_invoice DS INNER JOIN (SELECT * FROM 	`workflow_history` where doc_type = 'SI' and status in('Submited') 
							and id in ($id_var) ) DS1 
							ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Prepared') order by reviewed_by, create_by ";
				//echo $sql;
						$query = "SELECT  count(*) as num from sma_supplier_invoice DS INNER JOIN (SELECT * FROM 	`workflow_history` where doc_type = 'SI' and status in('Submited') 
							and id in ($id_var) ) DS1 
							ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Prepared') ";
					}
					else if($module=='E'){ //IPC
						$id_var = '';
						$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = 'IP'  group by doc_id";
						$qry = mysqli_query($con,$sql);
						while($rs = mysqli_fetch_array($qry)){
							$id_var .= $rs['idd'].',';
						
						}
						$id_var .= '0';
						$sql = "SELECT  DS.*, DS1.create_by, DS1.reviewed_by, DS1.remarks, DS1.create_date from sma_ipc DS INNER JOIN (SELECT * FROM 	`workflow_history` where doc_type = 'IP' and status in('Pending', 'Verified','Prepared') 
							and id in ($id_var) ) DS1 
							ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Prepared') order by reviewed_by, create_by ";
						
						$query = "SELECT  count(*) as num from sma_ipc DS INNER JOIN (SELECT * FROM 	`workflow_history` where doc_type = 'IP' and status in('Pending', 'Verified','Prepared') 
							and id in ($id_var) ) DS1 
							ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Prepared') ";
					}
					else if($module=='F'){ // Payment
						$id_var = '';
						$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = 'PY'  group by doc_id";
						$qry = mysqli_query($con,$sql);
						while($rs = mysqli_fetch_array($qry)){
							$id_var .= $rs['idd'].',';
						
						}
						$id_var .= '0';
						$sql = "SELECT  DS.*, DS1.create_by, DS1.reviewed_by, DS1.remarks, DS1.create_date from payment_header DS INNER JOIN (SELECT * FROM 	`workflow_history` where doc_type = 'PY' and status in('Pending', 'Verified','Submited') 
							and id in ($id_var) ) DS1 
							ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Submited') order by reviewed_by, create_by ";
						
						$query = "SELECT  count(*) as num from payment_header DS INNER JOIN (SELECT * FROM 	`workflow_history` where doc_type = 'PY' and status in('Pending', 'Verified','Submited') 
							and id in ($id_var) ) DS1 
							ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Submited') ";
					}
					else if($module=='G'){ // Travel Request
						$id_var = '';
						$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = 'TA'  group by doc_id";
						$qry = mysqli_query($con,$sql);
						while($rs = mysqli_fetch_array($qry)){
							$id_var .= $rs['idd'].',';
						
						}
						$id_var .= '0';
						$sql = "SELECT  DS.*, DS1.create_by, DS1.reviewed_by, DS1.remarks, DS1.create_date from sma_traval_approval DS INNER JOIN (SELECT * FROM 	`workflow_history` where doc_type = 'TA' and status in('Pending', 'Verified','Prepared') 
							and id in ($id_var) ) DS1 
							ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Prepared') order by reviewed_by, create_by ";
						
						$query = "SELECT  count(*) as num from sma_traval_approval DS INNER JOIN (SELECT * FROM 	`workflow_history` where doc_type = 'TA' and status in('Pending', 'Verified','Prepared') 
							and id in ($id_var) ) DS1 
							ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Prepared') ";
	//echo $sql. "<BR>";						
					}
					else if($module=='H'){
						$id_var = '';
						$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = 'TE'  group by doc_id";
						$qry = mysqli_query($con,$sql);
						while($rs = mysqli_fetch_array($qry)){
							$id_var .= $rs['idd'].',';
						
						}
						$id_var .= '0';
						$sql = "SELECT  DS.*, DS1.create_by, DS1.reviewed_by, DS1.remarks, DS1.create_date from sma_travel_expenses DS INNER JOIN (SELECT * FROM 	`workflow_history` where doc_type = 'TE' and status in('Pending', 'Verified','Submited') 
							and id in ($id_var) ) DS1 
							ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Submited') order by reviewed_by, create_by ";
						
						$query = "SELECT  count(*) as num from sma_travel_expenses DS INNER JOIN (SELECT * FROM `workflow_history` where doc_type = 'TE' and status in('Pending', 'Verified','Submited') 
							and id in ($id_var) ) DS1 
							ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Submited') ";
					}
					else if($module=='I'){ // Regular Expense 
						$id_var = '';
						$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = 'RE'  group by doc_id";
						$qry = mysqli_query($con,$sql);
						while($rs = mysqli_fetch_array($qry)){
							$id_var .= $rs['idd'].',';
						
						}
						$id_var .= '0';
						$sql = "SELECT  DS.*, DS1.create_by, DS1.reviewed_by, DS1.remarks, DS1.create_date from sma_travel_expenses DS INNER JOIN (SELECT * FROM 	`workflow_history` where doc_type = 'RE' and status in('Pending', 'Verified','Submited') 
							and id in ($id_var) ) DS1 
							ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Submited') order by reviewed_by, create_by ";
						
						$query = "SELECT  count(*) as num from sma_travel_expenses DS INNER JOIN (SELECT * FROM `workflow_history` where doc_type = 'RE' and status in('Pending', 'Verified','Submited') 
							and id in ($id_var) ) DS1 
							ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Submited') ";
					}
					else if($module=='J'){ // Regular Expense 
						$id_var = '';
						$sql = "SELECT max(id) as idd FROM `workflow_history` where doc_Type = 'CE'  group by doc_id";
						$qry = mysqli_query($con,$sql);
						while($rs = mysqli_fetch_array($qry)){
							$id_var .= $rs['idd'].',';
						
						}
						$id_var .= '0';
						$sql = "SELECT  DS.*, DS1.create_by, DS1.reviewed_by, DS1.remarks, DS1.create_date from sma_travel_expenses DS INNER JOIN (SELECT * FROM 	`workflow_history` where doc_type = 'CE' and status in('Pending', 'Verified','Submited') 
							and id in ($id_var) ) DS1 
							ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Submited') order by reviewed_by, create_by ";
						
						$query = "SELECT  count(*) as num from sma_travel_expenses DS INNER JOIN (SELECT * FROM `workflow_history` where doc_type = 'CE' and status in('Pending', 'Verified','Submited') 
							and id in ($id_var) ) DS1 
							ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Submited') ";
					}
			
//echo $sql;			
					$_SESSION['sqlex'] = $sql;
			
			//echo $query."<BR>";
					$qresult = mysqli_query($con,$query);
					echo mysqli_error($con);
					$total_pages = mysqli_fetch_array($qresult);
					//$total_pages = mysqli_fetch_array(mysqli_query($con,$query));
					$total_pages = $total_pages[num];
			//echo $total_pages. ' <<<>>';		
					
					$stages = 3;
					//$page = mysqli_real_escape_string($_GET['page']);
					
					$page = ($_GET['page']);
					if($page){
						$start = ($page - 1) * $limit; 
					}
					else{
						$start = 0;	
					}	
						
					//$sql .= ' order by id desc ';
					$sql .= " LIMIT $start, $limit ";
					
					
//echo $sql;			
	// Initial page num setup
	if ($page == 0){$page = 1;}
	$prev = $page - 1;	
	$next = $page + 1;							
	$lastpage = ceil($total_pages/$limit);		
	$LastPagem1 = $lastpage - 1;					
	
	$paginate = '';
	//echo $lastpage;
	//echo $paginate;
	if($lastpage > 1)
	{	
		$paginate .= '<div style="float:right"><ul class="pagination pagination-lg">';
		// Previous
		if ($page > 1){
			$paginate.= "<li><a href='$targetpage&page=$prev'>previous</a></li>";
		}else{
			$paginate.= "<li class='disabled'><a>previous</a></li>";	}
			
		// Pages	
		if ($lastpage < 7 + ($stages * 2))	// Not enough pages to breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page){
					$paginate.= "<li class='active'><a>$counter</a></li></span>";
				}else{
					$paginate.= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";}
			}
		}
		elseif($lastpage > 5 + ($stages * 2))	// Enough pages to hide a few?
		{
			// Beginning only hide later pages
			if($page < 1 + ($stages * 2))		
			{
				for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
				{
					if ($counter == $page){
						$paginate.= "<li class='active'><a>$counter</a></li>";
					}else{
						$paginate.= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";}
				}
				$paginate.= "<li><a>...</a></li>";
				$paginate.= "<li><a href='$targetpage&page=$LastPagem1'>$LastPagem1</a></li>";
				$paginate.= "<li><a href='$targetpage&page=$lastpage'>$lastpage</a></li>";		
			}
			// Middle hide some front and some back
			elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
			{
				$paginate.= "<li><a href='$targetpage&page=1'>1</a></li>";
				$paginate.= "<li><a href='$targetpage&page=2'>2</a></li>";
				$paginate.= "<li><a>...</a></li>";
				for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
				{
					if ($counter == $page){
						$paginate.= "<li class='active'><a>$counter</a></li>";
					}else{
						$paginate.= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";}					
				}
				$paginate.= "<li><a>...</a></li>";
				$paginate.= "<li><a href='$targetpage&page=$LastPagem1'>$LastPagem1</a></li>";
				$paginate.= "<li><a href='$targetpage&page=$lastpage'>$lastpage</a></li>";
			}
			// End only hide early pages
			else
			{
				$paginate.= "<li><a href='$targetpage&page=1'>1</a></li>";
				$paginate.= "<li><a href='$targetpage&page=2'>2</a></li>";
				$paginate.= "<li><a>...</a></li>";
				for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page){
						$paginate.= "<li class='active'><a>$counter</a></li>";
					}else{
						$paginate.= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";}
				}
			}
		}
					
				// Next
		if ($page < $counter - 1){ 
			$paginate.= "<li><a href='$targetpage&page=$next'>next</a></li>";
		}else{
			$paginate.= "<li class='disabled'><a>next</a></li>";
			}
			
		$paginate.= "</ul></div>";
}
//end page
				$today_date 	= strtotime(date("Y-m-d"));
				
				$result = mysqli_query($con, $sql);
				echo mysqli_error($con);
					
				while($row = mysqli_fetch_array($result)){
						
					$sent_by 		= $row['create_by'];
					$send_to		= $row['reviewed_by'];
					$remarks 		= $row['remarks'];
					$sent_date 	= date('d-m-Y', strtotime($row['create_date']));

					$dated 		= date('d-m-Y', strtotime($row['dated']));
					
					if($module=='A'){
						$company 	= $row['company'];
						$dated 		= date('d-m-Y', strtotime($row['dated']));
						$approval_hdr_id = $row['id'];
						$hdr_id		= $row['id'];
						$sql 		= "SELECT `values` as total_amount FROM `sma_approval_details` where vendor_selected = 'Y' and approval_hdr_id = '$approval_hdr_id' ";
						$r4 		= mysqli_query($con, $sql);
						$r3 		= mysqli_fetch_array($r4);
						$amount		= $r3['total_amount'];
										
						$sql 		= "SELECT b.party_name, a.values FROM `sma_approval_details` a , `sma_party_mst` b where vendor_selected = 'Y' and approval_hdr_id = '$approval_hdr_id' and b.id = a.supplier_name";
						$q2  		= mysqli_query($con, $sql);
						$r2 		= mysqli_fetch_array($q2);
						$party_name  = $r2['party_name'];
						$amount		 = $r2['values'];
						
					}
					else if($module=='B'){
						
						$purchase_id	= $row['id'];
						$supplier_id 	= $row['to_supplier'];
						$hdr_id	 	= $row['po_number'];
						$company	 	= $row['project'];						
						$sql 			= "SELECT party_name, '' FROM `sma_party_mst` where id = '$supplier_id' ";
						$q2  			= mysqli_query($con, $sql);
						$r2 			= mysqli_fetch_array($q2);
						$party_name  	= $r2['party_name'];
						$amount		 	= $r2['values'];
						
						$sql = "SELECT round(sum( (quantity * unit_rate ) +  (((quantity * unit_rate) * gst) / 100) ),2) as po_total  from sma_po_items where purchase_id = '$purchase_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res1);
						$amount 	= $r1['po_total'];
							
					}
					else if($module=='C'){
						$our_po_ref_no = $row['our_po_ref_no'];
						$dated 		= date('d-m-Y', strtotime($row['received_date']));
						$hdr_id			= $row['id'];
						$supplier_id 	= $row['supplier_name'];
						$company		= $row['supplier_invoice_no'];
						//$company	 	= $row['project'];						
						$sql 			= "SELECT party_name, '' FROM `sma_party_mst` where id = '$supplier_id' ";
						$q2  			= mysqli_query($con, $sql);
						$r2 			= mysqli_fetch_array($q2);
						$party_name  	= $r2['party_name'];
						//$amount		 	= $r2['values'];
						
						$sql="SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
						$q2 = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$amount		 = $r2['po_number'];
						
							
					}
					else if($module=='D'){
						$our_po_ref_no = $row['our_po_ref_no'];
						$dated 		= date('d-m-Y', strtotime($row['invoice_date']));
						$hdr_id			= $row['id'];
						$supplier_id 	= $row['suplier_name'];
						$company		= $row['supplier_invoice_no'];
						//$company	 	= $row['project'];						
						$sql 			= "SELECT party_name, '' FROM `sma_party_mst` where id = '$supplier_id' ";
						$q2  			= mysqli_query($con, $sql);
						$r2 			= mysqli_fetch_array($q2);
						$party_name  	= $r2['party_name'];
						//$amount		 	= $r2['values'];
						
						$sql="SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
						$q2 = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company	 = $r2['po_number'];
						
						$amount	 	= $row['total_amount'];
							
					}
					else if($module=='E'){  //IPC
						$our_po_ref_no = $row['sma_po_no'];
						$dated 			= date('d-m-Y', strtotime($row['ipc_date']));
						$hdr_id			= $row['id'];
						$supplier_id 	= $row['sma_vendor_id'];
						$company		= $row['supplier_invoice_no'];
						$company	 	= $row['sma_comp_id'];						
						$sql 			= "SELECT party_name, '' FROM `sma_party_mst` where id = '$supplier_id' ";
						$q2  			= mysqli_query($con, $sql);
						$r2 			= mysqli_fetch_array($q2);
						$party_name  	= $r2['party_name'];
						//$amount		 	= $r2['values'];
						
						$sql="SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
						$q2 = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company	 = $r2['po_number'];
						
						$amount	 	= $row['sma_po_amount'];
						$sma_invoice_no = $row['sma_invoice_no'];
						$sql = "select * from sma_supplier_invoice where id = '$sma_invoice_no' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$sma_invoice_no = $r2['supplier_invoice_no'];
							
					}
					else if($module=='F'){  //Payment
						$dated 			= date('d-m-Y', strtotime($row['dated']));
						$hdr_id			= $row['id'];
						
						$paid_to = $row['paid_to'];
						$st_flag = $row['st_flag'];
						if($st_flag =='A' || $st_flag =='T'){
							$sql = "SELECT * FROM `sma_user` where id = '$paid_to' ";
							$q2  = mysqli_query($con, $sql);
							$r2  = mysqli_fetch_array($q2);
							$party_name  = $r2['username'];
						}
						else {
							$sql = "SELECT party_name FROM `sma_party_mst` where id = '$paid_to' ";
							$q2  = mysqli_query($con, $sql);
							$r2  = mysqli_fetch_array($q2);
							$party_name  = $r2['party_name'];
						}
						
						$cash_bank_name = $row['cash_bank_name'];
						$sql 	= "select * from account_mst where id = '$cash_bank_name' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$company = $r2['account_name'];
						
						$amount	 	= $row['total_amount_paid'];
						$sql = "SELECT supplier_invoice_no FROM `payment_details` where payment_hdr_id = '$hdr_id' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$sma_invoice_no  = $r2['supplier_invoice_no'];
							
					}
					else if($module=='G'){  //Travel Request
						
						$emp_id = $row['emp_id'];
						$sql = "select * from sma_user where id = '$emp_id' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$emp_name = $r2['username'];
						
						$company_id  = $row['company_id'];
						$sql  = "SELECT * from company where comp_id = '$company_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 	= mysqli_fetch_array($res1);
						$comp_name 		= $r1['comp_name'];
						$comp_code 		= $r1['comp_code'];
						
						$start_date = date('d-m-Y', strtotime($row['start_date']));
						$end_date 	= date('d-m-Y', strtotime($row['end_date']));
						
						if($start_date=='01-01-1970'){ $start_date='';}
						if($end_date=='01-01-1970'){ $end_date='';}
					}
					else if($module=='H'){  //Travel Expense{
						$company_id  = $row['company_id'];
						$sql  = "SELECT * from company where comp_id = '$company_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 	= mysqli_fetch_array($res1);
						$comp_name 		= $r1['comp_name'];

						$emp_id = $row['emp_id'];
						$sql="SELECT * from sma_user where id = '$emp_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res1);
						$username		= $r1['username'];
						
						$dated 			=  date('d-m-Y', strtotime($row['dated']));
						if( $dated=='01-01-1970' ){ $dated='';}
						
						$exp_amount = 0;
						$fare		 = 0;
						$approval_ref_no = $row['approval_ref_no'];	
						$id = $row['id'];	
						$sql="SELECT * from sma_departure where approval_ref_no = '$id' ";
						$res = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$j = 0;
						while($d1 = mysqli_fetch_array($res)){
							$fare += $d1['fare'];
						}			
									
						$sql="SELECT * from sma_expenses where exp_type = 'T' and approval_ref_no = '$id' ";
						
						$res = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$j = 0;
						while($d1 = mysqli_fetch_array($res)){
							$exp_amount += $d1['amount'];
						}			
							
					}
					else if($module=='I'){  //Regular Expense{
						$company_id  = $row['company_id'];
						$sql  = "SELECT * from company where comp_id = '$company_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 	= mysqli_fetch_array($res1);
						$comp_name 	= $r1['comp_name'];

						$emp_id = $row['emp_id'];
						$sql  = "SELECT * from sma_user where id = '$emp_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res1);
						$username		= $r1['username'];
						
						$re_id = $row["id"];
						$sql  = "SELECT sum(amount) as amount FROM `sma_expenses` where exp_type = 'R' and approval_ref_no = '$re_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res1);
						$amount		= $r1['amount'];
						 
						$dated 		=  date('d-m-Y', strtotime($row['dated']));
						if( $dated=='01-01-1970' ){ $dated='';}
						
					}	
					else if($module=='J'){  //Operating Expense{
						$company_id  = $row['company_id'];
						$sql  = "SELECT * from company where comp_id = '$company_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 	= mysqli_fetch_array($res1);
						$comp_name 	= $r1['comp_name'];

						$sma_vendor_id = $row['emp_id'];
						$sql  = "SELECT * from sma_party_mst where id = '$sma_vendor_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 	= mysqli_fetch_array($res1);
						$username		= $r1['party_name'];
						
						$re_id = $row["id"];
						$sql  = "SELECT sum(amount) as amount FROM `sma_expenses` where exp_type = 'C' and approval_ref_no = '$re_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res1);
						$amount		= $r1['amount'];
						 
						$dated 		=  date('d-m-Y', strtotime($row['dated']));
						if( $dated=='01-01-1970' ){ $dated='';}
						
					}
					
					if($module=='A' || $module=='B' ){
						$sql 		= "select * from company where comp_id = '$company' ";
						$q2  		= mysqli_query($con, $sql);
						$r2 		= mysqli_fetch_array($q2);
						$company  	= $r2['comp_name'];
					}
					
					$sl="SELECT * FROM sma_user where id = '$sent_by' ";
					$r3 = mysqli_query($con, $sl);
					$rw = mysqli_fetch_array($r3);
					$sent_by = $rw['username'];
									
					$sl="SELECT * FROM sma_user where id = '$send_to' ";
					$r3 = mysqli_query($con, $sl);
					$rw = mysqli_fetch_array($r3);
					$send_to = $rw['username'];
										
						$j=$j+1;						
						
					$s_date 		= strtotime(date('Y-m-d', strtotime($row['create_date'])));
					//$your_date 	= strtotime("2010-01-31");
					$datediff	 	= $today_date - $s_date;
					$pending_days 	= round(($datediff / (60 * 60 * 24))) + 1 ;
					
//	echo $today_date . ' - ' . $s_date . ' ' . $pending_days;		
//exit();
				$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$row["id"];
				
		?>
			<tr>
				<?php if($module!='G' && $module!='H' && $module!='J' && $module!='I'){	 ?>	
					<td> <?php echo $hdr_id;?></td>
					<td> <?php echo $dated;?></td>
					<td> <?php echo $company;?></td>
					<td> <?php echo $party_name;?></td>
					<?php if($module=='F'){ ?>
					<td> <?php echo $sma_invoice_no;?></td>
					<?php } ?>
					<td style='text-align: right;' > <?php echo $amount;?></td>
					
				<?php } else if($module=='G'){	 ?>
					<td width="4%" style="text-align:right;"><?php echo $row['id'];?></td>
					<td width="10%"><?php echo $emp_name;?></td>
					<td width="10%"><?php echo $row['traval_from'];?></td>
					<td width="10%"><?php echo $start_date;?></td>
					<td width="13%"><?php echo $row['traval_to'];?></td>
					<td width="10%"><?php echo $end_date;?></td>
					<td width="04%"><?php echo $comp_code;?></td>
					<td width="10%"><?php echo $row['advance_amount'];?></td>
				<?php }  
					else if($module=='H'){	 ?>
					<td width="4%"><?php echo $row['id'];?></td>
					<td width="15%"><?php echo $username;?></td>
					<td width="15%"><?php echo $comp_name;?></td>
					<td width="10%"><?php echo $dated;?></td>
					<td width="10%"><?php echo $row['approval_ref_no'];?></td>
					<td width="10%" style="text-align:right;"><?php echo number_format($fare);?></td>
					<td width="10%" style="text-align:right;"><?php echo number_format($exp_amount);?></td>
				<?php } 
					else if($module=='I' || $module=='J'){  //Regular Expense{
					?>	
						<td width="4%"><?php echo $row['id'];?></td>
						<td width="20%"><?php echo $username;?></td>
						<td width="29%"><?php echo $comp_name;?></td>
						<td width="10%"><?php echo $dated;?></td>
						<td width="10%" style="text-align:right;"><?php echo $amount;?></td>
				<?php }	?>
					
					
					<td> <?php echo $sent_by ;?></td>
					<td> <?php echo $sent_date ;?></td>
					<td> <?php echo $send_to ;?></td>
					<td> <?php echo $pending_days ;?></td>
					
					
			</tr>
	<?php } ?>
				
                
                </tbody>
                <tfoot>
                
                </tfoot>
              </table>			  

<?php
  $end  =$start+10;
  $begin=$start+1;
  
  if ($end>$total_pages){ $end=$total_pages;}
	echo 'Showing ' . $begin .' to ' . $end . ' of ' . $total_pages . ' entries ';
	echo $paginate;
  
?>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

  <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<div class="modal fade" id="checkerAuthority" role="dialog" aria-labelledby="checkerAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="checkerAuthority">Send To... </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										
										<input type="hidden" name="ap_id" id="ap_idE" value="<?php echo $ap_id; ?>" >
										<input type="hidden" id="modeC" name="mode" value='Checker'>
										
										<div class="form-group col-md-12">
                                        
											<label for="approver" class="col-sm-3 control-label">User Name</label>
                                            <div class="col-sm-9">
                                                <span id="getuser">
													<select class="form-control select2123" id="approverE" name="approver" required>
													    <option value="">Select</option>
														<?php
												
															$sql="SELECT * FROM sma_user ORDER BY username ASC";
														    $result1 = mysqli_query($con, $sql);
														    echo mysqli_error($con);
														    while($row1 = mysqli_fetch_array($result1)){
														?>
														<option value="<?php echo $row1['id']?>" ><?php echo $row1['username'] ?></option>
													    <?php } ?>
													</select>
												</span>
                                            </div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksE"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitChecker">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Checker Workflow Popup End -->
<?php
include("footer.php");
?>

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(function () {
        $("#prtable").DataTable();
    });

	
function getsearchf(id){
    var sub = 'sub1';
//alert(id);

//	var searchf = document.getElementById("searchf").value;
//alert(searchf);	
	var strURL = "search_func.php";
	$.post(strURL,{ sub1:sub,id:id},function(result){
			  $('#getsearchf').html(result);
		});
}
	
  $("#submitChecker").on("click", function(e){
        var sub = 'mail';
		var mail		 	=  'mail';
		
		var approver		=  $("#approverE option:selected").val();
        var remarks			=  $("#remarksE").val();

//	alert(sub + ' ' + approver + ' '+ mail);
	
		if(approver==''){
			alert("User Name should select...");
			return;
		}

//alert(sub + ' ' + approver + ' '+ mail);
			
		 $('#checkerAuthority').modal('hide');
		var strURL = "pending_trans_repo.php";
		$.post(strURL,{ approver:approver,
						remarks:remarks,
						mail:mail,
						sub:sub},
						function(result){
		      $('#predit').html(result);
		});
		
		alert('Mail Sent.....');
		
	});
	
</script>

</body>
</html>

