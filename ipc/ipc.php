<?php

include("../header.php");
$modulePath = "ipc/";

	$help_code = $modulePath.'ipc.php';
	include "../help_code.php";
	
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        IPC
		<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">IPC</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
				<?php 
			
				$targetpage = "ipc.php?sub=list"; 
				$limit = 10; 
				$start = 0;	
				
			//echo $_POST['status']. ' ' . $_POST['comp_id'] ;	
			
				if ($_POST['comp_id'] or $_POST['status'] or $_POST['approval_status'] or $_POST['searchf'] or $_POST['search_own']){
					$_SESSION['comp_id'] = $_POST['comp_id'];
					$_SESSION['statuss'] = $_POST['status'];
					$_SESSION['approval_status'] = $_POST['approval_status'];
					$_SESSION['searchf'] = $_POST['searchf'];
					$_SESSION['search_own'] = $_POST['search_own'];
					$_SESSION['search_data'] = $_POST['search_data'];
					$_SESSION['start_date'] = $_POST['start_date'];
					$_SESSION['end_date'] = $_POST['end_date'];
					
				}
				
				if ($_SESSION['comp_id'] or $_SESSION['approval_status'] or $_SESSION['statuss'] or $_SESSION['searchf'] or $_SESSION['search_own'] ){
					$comp_id = $_SESSION['comp_id'];
					$status = $_SESSION['statuss'];
					$approval_status = $_SESSION['approval_status'];
					$searchf = $_SESSION['searchf'];
					$search_own		= $_SESSION['search_own'];
					$search_data = $_SESSION['search_data'];
					$start_date = $_SESSION['start_date'];
					$end_date = $_SESSION['end_date'];
					$_SESSION['reset']='';
				}
				
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['comp_id'] = '';
					$_SESSION['statuss'] = '';
					$_SESSION['approval_status'] = '';
					$_SESSION['searchf'] = '';
					$_SESSION['search_own'] = '';
					$_SESSION['search_data'] = '';
					$_SESSION['start_date'] = '';
					$_SESSION['end_date'] = '';
					$comp_id = $_SESSION['comp_id'];
					$status = $_SESSION['statuss'];
					$searchf = $_SESSION['searchf'];
					$search_own = $_SESSION['search_own'];
					$search_data = $_SESSION['search_data'];
					$start_date = $_SESSION['start_date'];
					$end_date = $_SESSION['end_date'];
					$approval_status = $_SESSION['approval_status'];
					
				}
				
				//if(!$_POST['Save']){
				//	$search_own='Y';
				//}
				
			?>
								<form class="form-horizontal" action="ipc.php?sub=list" method="post">
                      
						<div class="form-group">
								
								<label class="col-lg-1 control-label">Supplier Name</label>
								<div class="col-md-3">
									<select class="form-control select2" name="comp_id" id="comp_id"  >
										<option value=""> Select </option>
											<?php $sql = "select * from sma_party_mst order by party_name  ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>" <?php echo ($comp_id == $r2['id'])?'selected="selected"':'';?>  ><?php echo $r2['party_name'];?></option>
											<?php } ?>
									</select>
										
								</div>
								
								<label for="reqDate" class="col-lg-1 control-label">Status</label>
								<div class="col-md-2">
                                       <select class="form-control select2" name="status" id="status" >
										<option value=""> Select </option>
										<option value="Draft" <?php echo ($status == 'Draft')?'selected="selected"':'';?> > Draft </option>
										<option value="Submitted" <?php echo ($status == 'Submitted')?'selected="selected"':'';?>> Submitted </option>
										<option value="Completed" <?php echo ($status == 'Completed')?'selected="selected"':'';?>> Completed </option>
										<option value="Previous" <?php echo ($status == 'Previous')?'selected="selected"':'';?>> Previous </option>
										</select>
								</div>

								<label for="reqDate" class="col-lg-1 control-label">Decision</label>
								<div class="col-md-2">
                                       <select class="form-control select2" name="approval_status" id="approval_status" >
										<option value=""> Select </option>
										<option value="Approved" <?php echo ($approval_status == 'Approved')?'selected="selected"':'';?>> Approved </option>
										<option value="Rejected" <?php echo ($approval_status == 'Rejected')?'selected="selected"':'';?>> Rejected </option>
										</select>
								</div>
								
							<div class="col-xs-2">
                                		
							<input class="btn btn-primary" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
							<a href="ipc.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>
							
						</div>

						<div class="form-group">
						
							<label class="col-lg-1 control-label">Search.On</label>
							<div class="col-md-2">
								<select class="form-control select2123" name="searchf" id="searchf" onchange="getsearchf(this.value)">
									<option value=""> Select </option>
									<option value="S" <?php echo ($searchf == 'S')?'selected="selected"':'';?>> Company Name </option>
									<option value="D" <?php echo ($searchf == 'D')?'selected="selected"':'';?>> Date </option>
									<option value="N" <?php echo ($searchf == 'N')?'selected="selected"':'';?>> Sr.No. </option>
									<option value="P" <?php echo ($searchf == 'P')?'selected="selected"':'';?>> PO.No. </option>
									<option value="O" <?php echo ($searchf == 'O')?'selected="selected"':'';?>> OWN </option>
									<option value="V" <?php echo ($searchf == 'V')?'selected="selected"':'';?>> Deactive </option>
									<option value="B" <?php echo ($searchf == 'B')?'selected="selected"':'';?>> Both </option>
								</select>											
							</div>
							
							<span id="getsearchf">
							<?php if($searchf=='N' || $searchf=='S'){ ?>	
								<div class="col-md-3">
								<?php if($searchf=='N'){ ?>
									<input type="text" class="form-control" id="search_data" name="search_data" autocomplete="off" value="<?php echo $search_data?>" >
								<?php } ?>
								
							<?php if($searchf=='S'){ ?>
									<select class="form-control select2-123" name="search_data">
									<option value=""> Select </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>"  <?php echo ($search_data == $r2['comp_id'])?'selected="selected"':'';?> ><?php echo $r2['comp_name'];?></option>
										<?php } ?>
                                    </select>
							<?php } ?>
							
								</div>
							<?php } ?>
							
							<?php if($searchf=='D'){ 
								$start_date = date('d-m-Y', strtotime($start_date));
								$end_date = date('d-m-Y', strtotime($end_date));
								if($start_date =='01-01-1970'){
									$start_date = date('d-m-Y');
								}
								if($end_date =='01-01-1970'){
									$end_date = date('d-m-Y');
								}
							?>	
								<label class="col-lg-1 control-label">Start.Date</label>
								<div class="col-md-2">
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="<?php echo $start_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
								<label class="col-lg-1 control-label">End.Date</label>
								<div class="col-md-2">
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="<?php echo $end_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
							<?php } ?>
									
							</span>
						
						<?php	
							$checked = '';
							//echo $search_own." >><<";
							if($search_own=='Y'){$checked ='CHECKED';}
						?>
								<span>
									<div class="col-md-2" ><b class="btn btn-info">Own</b>&nbsp;&nbsp;
										<input type="checkbox"  <?php echo $checked;?> id="search_own" name="search_own" value="Y" >
									</div>
								</span>
								
								<span class="pull-right"><?php for($x=0;$x<10;$x++){echo '&nbsp;';}?></span>
															
							  <span class="pull-right"><a href="ipc_export.php?sub=pdf" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp; Report </a></span>
							  
							
								<span class="pull-right"><a href="ipc.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create IPC </a>&nbsp;&nbsp;&nbsp;</span>
							

						</div>
						
				</form>

            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>#</th>
			<th>Sr.No.</th>
			<th>Dated</th>
			<th>Party</th>
			<th>PO.Number</th>
			<th>Invoice No.</th>
			<th>PO Amount</th>
			<th>Invoice Amount</th>
			<th>By</th>
			<th>Status</th>
			<th>Decision</th>
			
<!--			<th style="text-align:right;">Action</th>-->
			
		</tr>
	</thead>
<tbody>
<?php
	$user   = $_SESSION['user'];
	$comid = $_SESSION['comid'];
	
	$role			= $_SESSION['role'];
	$user_category	= $_SESSION['user_category'];

	$sql = "select * from sma_role where id = '$primary_role' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$row = mysqli_fetch_array($result);
	$primaryrole = $row['role'];
					
	$sql="SELECT * from sma_ipc where sma_comp_id in ( $comid ) and (status = 'Submitted' or status = 'Completed' or draft_by = '$user')";
	
	$query="SELECT count(*) as num  from sma_ipc where sma_comp_id in ( $comid ) and (status = 'Submitted' or status = 'Completed' or draft_by = '$user')";
	
	if ( $user =='Admin'  || $primaryrole=='COO' || $primaryrole == 'Director' ){
		$sql="SELECT * from sma_ipc where id > 0 ";
		$query="SELECT count(*) as num  from sma_ipc where id > 0 ";
	}
	
					if ($comp_id){
						$sql .= " and sma_vendor_id =  '$comp_id' ";
						$query .= " and sma_vendor_id =  '$comp_id' ";
					}
					if ( $status=='Previous' ){
						$sql .= " and sma_inv_adv = 'P' ";
						$query .= " and sma_inv_adv = 'P' ";
					}
					else if ($status){
						$sql .= " and status = '$status' ";
						$query .= " and status = '$status' ";
					}
					if ($approval_status){
						$sql .= " and approval_status = '$approval_status' ";
						$query .= " and approval_status = '$approval_status' ";
					}
					
					if($searchf=='D'){
						$start_date = date('Y-m-d', strtotime($_SESSION['start_date']));
						$end_date = date('Y-m-d', strtotime($_SESSION['end_date']));
						$sql .= " and ipc_date >= '$start_date' and ipc_date <= '$end_date' ";
						$query .= " and ipc_date >= '$start_date' and ipc_date <= '$end_date' ";
					}
					if($searchf=='N'){
						$sql .= " and id = '$search_data' ";
						$query .= " and  id = '$search_data'  ";
					}
					if($searchf=='P'){
						$sql .= " and sma_po_no = '$search_data' ";
						$query .= " and  sma_po_no = '$search_data'  ";
					}
					if($searchf=='S'){
						$sql .= " and sma_po_no in ( SELECT id FROM `sma_purchase_order` where project = '$search_data' ) ";
						$query .= " and sma_po_no in ( SELECT id FROM `sma_purchase_order` where project = '$search_data' ) " ;
					}
					
					if($search_own=='Y'){
						$sql .= " and draft_by = '$user' ";
						$query .= " and  draft_by = '$user'  ";
					}

					//Active / Deactive records
					if($searchf=='V'){
						$sql .= " and del = 'Y' ";
						$query .= " and del = 'Y' ";
					}
					else if($searchf=='B'){
						$sql .= "";
						$query .= " ";
					}
					else { // Default
						$sql .= " and del != 'Y' ";
						$query .= " and del != 'Y' ";
					}
					
					
					$qresult = mysqli_query($con,$query);
					echo mysqli_error($con);
					$total_pages = mysqli_fetch_array($qresult);
					//$total_pages = mysqli_fetch_array(mysqli_query($con,$query));
					$total_pages = $total_pages['num'];
					
					$stages = 3;
					//$page = mysqli_real_escape_string($_GET['page']);
		
					$page = ($_GET['page']);
					if($page){
						$start = ($page - 1) * $limit; 
					}
					else{
						$start = 0;	
					}	
	
	$_SESSION['sqlex'] = $sql;	
					
	$sql .= ' order by id desc ';
	
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

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
//echo $sql;
	
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
		$po_rev	   = $r2['po_rev'];
		if($po_rev>0){
			$po_number = $po_number.'-'.$po_rev;
		}	
		
		$sma_invoice_no = $row['sma_invoice_no'];
		$sql = "select * from sma_supplier_invoice where id = '$sma_invoice_no' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$sma_invoice_no = $r2['supplier_invoice_no'];
		
		$ipc_date	= date('d-m-Y', strtotime($row['ipc_date']));
		if($ipc_date == '01-01-1970'){
			$ipc_date	='';
		}	
		
		$sma_inv_adv = $row['sma_inv_adv'];
		if($sma_inv_adv =='P'){
			$status = 'Previous';
		}
		else {
			$status = $row['status'];
		}
		
			$styl  = '';
			$styl2 = '';
			$del   = $row['del'];
			if($del =='Y'){
						
				$styl = "style='bgcolor:powderblue;color:red;' ";
				$styl2 = "bgcolor:powderblue;color:red; ";
							
			}
			
		$baseurl1 = $baseurl.$modulePath.'ipc.php?sub=edit&id='.$row["id"];
?>

	<a href="<?php echo $baseurl . $modulePath . "ipc.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="3%" <?php echo $styl; ?>><?php echo $row['id'];?></td>
		<td width="12%" <?php echo $styl; ?>><?php echo $ipc_date;?></td>
		<td width="25%" <?php echo $styl; ?>><?php echo $sma_vendor_name;?></td>
		<td width="9%" <?php echo $styl; ?>><?php echo $po_number;?></td>
		<td width="8%" <?php echo $styl; ?>><?php echo $sma_invoice_no;?></td>
		<td width="9%" <?php echo $styl; ?>><?php echo $row['sma_po_amount'];?></td>
		<td width="9%" <?php echo $styl; ?>><?php echo $row['sma_invoice_amount'];?></td>
		<td width="8%" <?php echo $styl; ?>><?php echo $row['changed_by'];?></td>
		<td width="8%" <?php echo $styl; ?>><?php echo $status;?></td>
		<td width="8%" <?php echo $styl; ?>><?php echo $row['approval_status'];?></td>

<!--	<td width="20%"><?php echo $comp_name;?></td>
	<td width="10%" style="text-align:right;">
		<a href="ipc.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		<a href="ipc.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
		</td>-->
    </tr>
	</a>
	<?php }?>
</tbody> 
</table>


<?php
  $end  =$start+10;
  $begin=$start+1;
  
  if ($end>$total_pages){ $end=$total_pages;}
  echo $paginate;
  echo 'Showing ' . $begin .' to ' . $end . ' of ' . $total_pages . ' entries ';
  
?>

	</div>
    </div>
</div>

    <?php }?>

<?php  
	if($_GET['sub'] == 'delete'){ 
        $id = $_GET['id'];
		//$sql="delete from sma_ipc where id='$id' ";
		$sql = "update sma_ipc set del = 'Y' where id='$id' ";		
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="ipc.php?sub=list";</script>';
		
	}
?>

<?php if($_GET['sub'] == 'add'){

	if(isset($_POST['Save'])){
 
			$ipc_date			= date('Y-m-d', strtotime($_POST['ipc_date']));
			$sma_comp_id		= $_POST['sma_comp_id'];
			$sma_vendor_id		= $_POST['sma_vendor_id'];
			$sma_po_no			= $_POST['sma_po_no'];
			$sma_inv_adv		= $_POST['sma_inv_adv'];
			$sma_invoice_no		= $_POST['sma_invoice_no'];
			$sma_po_amount		= $_POST['sma_po_amount'];
			$sma_invoice_amount			= $_POST['sma_invoice_amount'];
			$sma_variation_order_amt	= $_POST['sma_variation_order_amt'];
			$sma_variation_in_price		= $_POST['sma_variation_in_price'];
			$sma_material_advance		= $_POST['sma_material_advance'];
//			$sma_material_advance_recovery	= $_POST['sma_material_advance_recovery'];
			$sma_deduct_retention_money		= $_POST['sma_deduct_retention_money'];
			$sma_release_retention_money	= $_POST['sma_release_retention_money'];
			$sma_variation_due_to_arbitratioon		= $_POST['sma_variation_due_to_arbitratioon'];
			$sma_deduction_work_contract_tax		= $_POST['sma_deduction_work_contract_tax'];
			$sma_deduction_liquidated_damage		= $_POST['sma_deduction_liquidated_damage'];
			$sma_amount_withhold			= $_POST['sma_amount_withhold'];
			$release_withheld_amount		= $_POST['release_withheld_amount'];
			$sma_other_deduction			= $_POST['sma_other_deduction'];
			$sma_other_deduction_desc		= $_POST['sma_other_deduction_desc'];
			$other_addition					= $_POST['other_addition'];
			$other_addition_desc			= $_POST['other_addition_desc'];
			$other_statutory_deduction		= $_POST['other_statutory_deduction'];
			$other_statutory_deduction_desc	= $_POST['other_statutory_deduction_desc'];
			$remarks						= $_POST['remarks'];
			
			$status 			= 'Draft';

			$user   = $_SESSION['user'];

			$sma_material_advance_recovery	= '';
			
  			$sql="insert into sma_ipc (sma_comp_id, ipc_date, sma_vendor_id, sma_po_no, sma_inv_adv, sma_invoice_no, sma_po_amount, sma_invoice_amount, sma_variation_order_amt, sma_variation_in_price, sma_material_advance, sma_material_advance_recovery, sma_deduct_retention_money, sma_release_retention_money, sma_variation_due_to_arbitratioon, sma_deduction_work_contract_tax, sma_deduction_liquidated_damage,sma_amount_withhold, release_withheld_amount, sma_other_deduction, sma_other_deduction_desc, other_addition, other_addition_desc, other_statutory_deduction, other_statutory_deduction_desc, status, draft_by, draft_dated, remarks ) 
			Values( '$sma_comp_id', '$ipc_date', '$sma_vendor_id', '$sma_po_no', '$sma_inv_adv', '$sma_invoice_no', '$sma_po_amount', '$sma_invoice_amount', '$sma_variation_order_amt', '$sma_variation_in_price', '$sma_material_advance', '$sma_material_advance_recovery', '$sma_deduct_retention_money', '$sma_release_retention_money', '$sma_variation_due_to_arbitratioon', '$sma_deduction_work_contract_tax', '$sma_deduction_liquidated_damage', '$sma_amount_withhold', '$release_withheld_amount', '$sma_other_deduction', '$sma_other_deduction_desc', '$other_addition', '$other_addition_desc', '$other_statutory_deduction', '$other_statutory_deduction_desc', '$status', '$user', now(), '$remarks' )";
					
			$query= mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

			$ipc_id = mysqli_insert_id($con);
			$userid = $_SESSION['usrid'];
			$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, flow_flag ) 
						values('IP', '$ipc_id', '$userid', now(), '$status', 'P')";
			$query= mysqli_query($con, $sql);
			$error= mysqli_error($con);
			
			echo "IPC successful added";
			echo '<script>window.location.href="ipc.php?sub=list";</script>';
		}

?>

    <section class="content-header">
        <h1>
            IPC
            <small>Add</small>
			<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath."ipc.php?sub=list" ?>">IPC</a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            
            <!-- /.box-header -->
            <div class="box-body">
            <!-- form start -->
            <form class="form-horizontal" action="ipc.php?sub=add" method="post" enctype="multipart/form-data">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							<label class="col-lg-2 control-label">Company<span style="color:red;"> **</span></label>
							<div class="col-md-3">
								<select class="form-control" name="sma_comp_id" id="sma_comp_iD" autocomplete="off" required >
									<option value=""> Select </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['sma_comp_id'] == $r2['comp_id'])?'selected="selected"':'';?> > <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<label class="col-lg-1 control-label">Vendor<span style="color:red;"> **</span> </label>
							<div class="col-md-3">
								<select class="form-control" name="sma_vendor_id" id="sma_vendor_id" autocomplete="off" required onchange="getpono(this.value); getsuppno123(this.value) " >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['sma_vendor_id'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['party_name'];?></option>
										<?php } ?>
								</select>
							</div>

							<label class="col-lg-1 control-label">Date </label>
							<div class="col-md-2">
                                 <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                     <div class="input-group-addon">
                                          <i class="fa fa-calendar-alt"></i>
                                     </div>
                                     <input type="text" class="form-control" id="prDate" name="ipc_date" placeholder="dd/mm/yyyy" readonly
                                               value="<?php echo date('d-m-Y');?>">
                                 </div>
							</div>
														
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">PO Number<span style="color:red;"> **</span></label>
							<div class="col-md-3">
								<span id="getpono">
								<select class="form-control select2" name="sma_po_no" id="sma_po_no" autocomplete="off" required>
									<option value=""> Select </option>
										<?php $sql = "select * from sma_purchase_order order by po_number ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){
											$po_number 	= $r2['po_number'];
											$po_rev		= $r2['po_rev'];
											if($po_rev>0){
												$po_number = $po_number.'-'.$po_rev;
											}
											?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['sma_po_no'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $po_number;?></option>
										<?php } ?>
								</select>
							    </span>
							</div>
						
							<label class="col-lg-2 control-label">PO Amount</label>
							<div class="col-md-2">
								<span id="getpoamt">
									<input type="text" class="form-control" id="sma_po_amount" name="sma_po_amount" style="text-align:right;" autocomplete="off" value="">
								</span>	
							</div>
						
							
						</div>
						
						
						<div class="form-group">
						
						<label class="col-lg-1 control-label">&nbsp;</label>
							<div class="col-md-4">
							
								<label class="control-label">Against</label><br>
								<input type="radio" id="sma_inv_advI" name="sma_inv_adv" checked  value="I" > Invoice
								<input type="radio" id="sma_inv_advA" name="sma_inv_adv" value="A" > Advance
								<input type="radio" id="sma_inv_advR" name="sma_inv_adv" value="R" > Retention
								<input type="radio" id="sma_inv_advP" name="sma_inv_adv" value="P" > Previous
							
							</div>
							
							<div class="col-md-2">
								<label class=" control-label">Supplier Invoice Srno.<span style="color:red;"> **</span></label>
								<span id="getsuppno">
								<?php // where id not in(select sma_invoice_no from sma_ipc ) ?>
								<select class="form-control" name="sma_invoice_no" id="sma_invoice_no" autocomplete="off" required onchange = "getsuppamt(this.value)"  >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_supplier_invoice where 1 order by id desc ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['sma_invoice_no'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['id'];?></option>
										<?php } ?>
								</select>
								</span>	
							</div>
						
							<span id="getsuppamt">
								<div class="col-md-2"><label class="control-label">Invoice Number</label>
									<input type="text" class="form-control" readonly value="" >
								</div>
								<div class="col-md-2">
									<label class="control-label">Invoice Amount</label>

									<input type="text" class="form-control" id="sma_invoice_amount" name="sma_invoice_amount" style="text-align:right;" autocomplete="off" value="">
								</div>
							</span>	
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Variation Order Amount</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_variation_order_amt" name="sma_variation_order_amt" style="text-align:right;" autocomplete="off" value="">
							</div>
						
							<label class="col-lg-3 control-label">Variation in Price(VOP)</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_variation_in_price" name="sma_variation_in_price" style="text-align:right;" autocomplete="off" value="">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Material Advance </label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_material_advance" name="sma_material_advance" style="text-align:right;" autocomplete="off" value="">
							</div>
						
			<!--				<label class="col-lg-3 control-label">Material Advance Recovery</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_material_advance_recovery" name="sma_material_advance_recovery" style="text-align:right;" autocomplete="off" value="">
							</div>-->
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Deduct Retention Money</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_deduct_retention_money" name="sma_deduct_retention_money" style="text-align:right;" autocomplete="off" value="">
							</div>
						
							<!--<label class="col-lg-3 control-label">Release of Retention Money.</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_release_retention_money" readonly name="sma_release_retention_money" style="text-align:right;" autocomplete="off" value="">
							</div>
							-->
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Variation due to Arbitration/ Claims or disputes</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_variation_due_to_arbitratioon" name="sma_variation_due_to_arbitratioon" style="text-align:right;" autocomplete="off" value="">
							</div>
						
							<label class="col-lg-3 control-label">Deduction Work contract Tax</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_deduction_work_contract_tax" name="sma_deduction_work_contract_tax" style="text-align:right;" autocomplete="off" value="">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Deduction Liquidated Damage</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_deduction_liquidated_damage" name="sma_deduction_liquidated_damage" style="text-align:right;" autocomplete="off" value="">
							</div>
							
							<label class="col-lg-2 control-label">Amount Withhold</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_amount_withhold" name="sma_amount_withhold" <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="">
							</div>
							
							<label class="col-lg-2 control-label">Release of Withheld Amount</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="release_withheld_amount" name="release_withheld_amount" <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="">
							</div>
						
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Other Deduction </label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_other_deduction" name="sma_other_deduction" autocomplete="off" style="text-align:right;" value="">
							</div>
						
							<label class="col-lg-1 control-label"> Description</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="sma_other_deduction_desc" name="sma_other_deduction_desc" autocomplete="off"  value="">
							</div>
						</div>
						 
						
						<div class="form-group">
							
							<label class="col-lg-2 control-label">Other Addition </label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="other_addition" name="other_addition" autocomplete="off" style="text-align:right;" value="">
							</div>
						
							<label class="col-lg-1 control-label"> Description</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="other_addition_desc" name="other_addition_desc" autocomplete="off"  value="">
							</div>
						</div>
						
						<div class="form-group">
							
							<label class="col-lg-2 control-label">Other Statutory Deduction </label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="other_statutory_deduction" name="other_statutory_deduction" autocomplete="off" style="text-align:right;" value="">
							</div>
						
							<label class="col-lg-1 control-label"> Description</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="other_statutory_deduction_desc" name="other_statutory_deduction_desc" autocomplete="off"  value="">
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-12">
                                <div class="box-header"><span class="box-title">Remarks</span></div>
                                <div class="box-body">
                                   <textarea class="form-control" id="reason2" name="remarks"  <?php echo $readonly; ?>
                                        placeholder="Enter text ..."><?php echo  stripslashes($row['remarks']);?></textarea>
                                </div>
                            </div>
						</div>
							
						<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="ipc.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>
                        
                    </fieldset>
				</div>	
            </form>
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      </div>
  <!-- /.content-wrapper -->
</section>  

<?php } 	?>


<?php if($_GET['sub'] == 'edit'){

	if(isset($_POST['Save'])){
			$id			= $_POST['id']; 
			$ipc_id				= $_POST['id']; 
			$ipc_date			= date('Y-m-d', strtotime($_POST['ipc_date']));
			$sma_comp_id		= $_POST['sma_comp_id'];
			$sma_vendor_id		= $_POST['sma_vendor_id'];
			$sma_po_no			= $_POST['sma_po_no'];
			$sma_inv_adv		= $_POST['sma_inv_adv'];
			$sma_invoice_no		= $_POST['sma_invoice_no'];
			$sma_po_amount		= $_POST['sma_po_amount'];
			$sma_invoice_amount			= $_POST['sma_invoice_amount'];
			$sma_variation_order_amt	= $_POST['sma_variation_order_amt'];
			$sma_variation_in_price		= $_POST['sma_variation_in_price'];
			$sma_material_advance		= $_POST['sma_material_advance'];
//			$sma_material_advance_recovery	= $_POST['sma_material_advance_recovery'];
			$sma_deduct_retention_money		= $_POST['sma_deduct_retention_money'];
			$sma_release_retention_money	= $_POST['sma_release_retention_money'];
			$sma_variation_due_to_arbitratioon		= $_POST['sma_variation_due_to_arbitratioon'];
			$sma_deduction_work_contract_tax		= $_POST['sma_deduction_work_contract_tax'];
			$sma_deduction_liquidated_damage		= $_POST['sma_deduction_liquidated_damage'];
			$sma_amount_withhold			= $_POST['sma_amount_withhold'];
			$release_withheld_amount		= $_POST['release_withheld_amount'];
			$sma_other_deduction			= $_POST['sma_other_deduction'];
			$sma_other_deduction_desc		= $_POST['sma_other_deduction_desc'];
			$other_addition					= $_POST['other_addition'];
			$other_addition_desc			= $_POST['other_addition_desc'];
			$other_statutory_deduction		= $_POST['other_statutory_deduction'];
			$other_statutory_deduction_desc	= $_POST['other_statutory_deduction_desc'];
			$remarks	= $_POST['remarks'];

			$approver_1			= $_POST['approver_1'];
			$approver_2			= $_POST['approver_2'];
			$approver_3			= $_POST['approver_3'];
			$approver_4			= $_POST['approver_4'];
			$approver_5			= $_POST['approver_5'];
			$approver_6			= $_POST['approver_6'];
			$approver_7			= $_POST['approver_7'];
			$approver_8			= $_POST['approver_8'];

			$status				= $_POST['status'];
			
  			$sql="update sma_ipc set 	sma_vendor_id = '$sma_vendor_id', 
						ipc_date			= '$ipc_date',
						sma_comp_id 		= '$sma_comp_id',						
						sma_po_no			= '$sma_po_no',
						sma_inv_adv			= '$sma_inv_adv',
						sma_invoice_no		= '$sma_invoice_no',
						sma_po_amount		= '$sma_po_amount',
						sma_invoice_amount			= '$sma_invoice_amount',
						sma_variation_order_amt		= '$sma_variation_order_amt',
						sma_variation_in_price		= '$sma_variation_in_price',
						sma_material_advance		= '$sma_material_advance',
						sma_deduct_retention_money		= '$sma_deduct_retention_money',
						sma_release_retention_money		= '$sma_release_retention_money',
						sma_variation_due_to_arbitratioon	= '$sma_variation_due_to_arbitratioon',
						sma_deduction_work_contract_tax		= '$sma_deduction_work_contract_tax',
						sma_deduction_liquidated_damage		= '$sma_deduction_liquidated_damage',
						sma_amount_withhold				= '$sma_amount_withhold',
						release_withheld_amount			= '$release_withheld_amount',
						sma_other_deduction				= '$sma_other_deduction',
						sma_other_deduction_desc		= '$sma_other_deduction_desc',
						other_addition					= '$other_addition',
						other_addition_desc				= '$other_addition_desc',
						other_statutory_deduction		= '$other_statutory_deduction',
						other_statutory_deduction_desc	= '$other_statutory_deduction_desc',
						remarks	= '$remarks'
					where id='$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			if( !empty($approver_1) && $status == 'Draft' ){
				$status				= 'Submitted';
				$approver_1_status  = 'Submitted';
				$sql = "update sma_ipc set current_approver = '$approver_1',
						approver_1			= '$approver_1',
						approver_2			= '$approver_2',
						approver_3			= '$approver_3',
						approver_4			= '$approver_4',
						approver_5			= '$approver_5',
						approver_6			= '$approver_6',
						approver_7			= '$approver_7',
						approver_8			= '$approver_8',
						approver_1_status	= '$approver_1_status',
						status				= '$status'
					where id='$ipc_id'";	
				$query=mysqli_query($con, $sql);	
				
				$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
					values( 'IP', '$ipc_id', '$userid', now(), 'Submitted', '$approver_1', now() ) ";
					$query=mysqli_query($con, $sql);
					$error= mysqli_error($con);
					if(!empty($error)){echo $error; exit();}
					
				$modulePath = "ipc/";
				
				$sql="select * from sma_user where id='$approver_1' and active='1' ";				
				$result = mysqli_query($con, $sql);
				while($r = mysqli_fetch_object($result)){
					$username 		= $r->userid;
					$id		 		= $r->id;
					$role	 		= $r->role;
					$company_id 	= $r->company_id;
					$user_email		= $r->email;
					$user_name		= $r->username;
				}
				
				$baseurl1 =$baseurl.$modulePath.'ipc.php?sub=edit&id='.$ipc_id;
				
				$msg = 'IPC  Number : '.$ipc_id . ' ' . 'Dated : ' . date("d-m-Y");
				
				include "ipc_mail.php";	
					
			}
			
			if( $sma_inv_adv=='A' && empty($sma_invoice_no) ){
				if($sma_invoice_no!='0'){
					echo '<script>alert("Please select Supplier Invoice advance from dropdown ...");</script>';
				}
			}
			
			// add attachments
			// file upload
			$arrDocType = $_POST["doctype"];
			$arrDocDesc = $_POST["docdesc"];
			$arrFUDoc = $_FILES["fudoc"];
			for($i = 0; $i < sizeof($arrDocType); $i++) {
				$folder_path = "uploads/ip/" . $ipc_id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
					
					$findex = $folder_path.'/index.php';
					fopen($findex,'w');
				}
				$filename = $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];
				
				if( !empty($filename) ){
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, reference_id, date_uploaded) VALUES('IP', '" . $filename . "', '" . $folder_path . "', '" . $arrDocType[$i] . "', '" . $arrDocDesc[$i] . "', " . $ipc_id . ", now() )";
					if (mysqli_query($con, $sql)) {
						move_uploaded_file($tmpFileName, "uploads/ip/" . $ipc_id . "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					}
				}
			}


//DMS Doc upload			
			$party_doc = $_POST['party_doc'];
			for($i = 0; $i < sizeof($party_doc); $i++){
			
				$doc_in = $party_doc[$i];
				$sql = "SELECT module, file_path, file_name, reference_id, doc_type FROM `my_documents_files` where id = '$doc_in' ";
				//echo $sql. "<BR>";
				$res = mysqli_query($con, $sql);
				$r11 			= mysqli_fetch_array($res);
				$filename 		= $r11['file_name'];
				$folder_path 	= $r11['file_path'];
				$arrDocType 	= $r11['doc_type'];
				$reference_id 	= $r11['reference_id'];
				
				$sql = "INSERT INTO file_uploads ( module, dms_module, file_name, file_path, doc_type, reference_id, doc_invoice_no, date_uploaded ) 
				VALUES( 'IP', 'IN', '$filename', '$folder_path', '$arrDocType', '$ipc_id', '$reference_id', now() )";
				mysqli_query($con, $sql);
				//echo $sql. "<BR>";
				
				//exit();
			}
//DMS Doc upload	

	//exit();
	
			echo '<script>window.location.href="ipc.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from sma_ipc where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

		$status = $row['status'];
		$approval_status = $row['approval_status'];
		$del = $row['del'];
		$ipc_id 	= $id;
								
		$readonly = '';
		if (($status == 'Submitted' || $status == 'Completed') && $user!='Admin' ){
			$readonly = 'READONLY';
		}

		if($del=='Y'){
			$readonly = 'READONLY';
		}	
		
		$psma_invoice_amount			= $row['sma_invoice_amount'];
		$psma_variation_order_amt		= $row['sma_variation_order_amt'];
		$psma_variation_in_price		= $row['sma_variation_in_price'];
		$psma_material_advance			= $row['sma_material_advance'];
		$psma_material_advance_recovery	= $row['sma_material_advance_recovery'];
		$psma_deduct_retention_money	= $row['sma_deduct_retention_money'];
		$psma_release_retention_money	= $row['sma_release_retention_money'];
		$psma_variation_due_to_arbitratioon		= $row['sma_variation_due_to_arbitratioon'];
		$psma_deduction_work_contract_tax		= $row['sma_deduction_work_contract_tax'];
		$psma_deduction_liquidated_damage		= $row['sma_deduction_liquidated_damage'];
		$psma_other_deduction			= $row['sma_other_deduction'];
		$psma_other_deduction_desc		= $row['sma_other_deduction_desc'];
		$psma_amount_withhold			= $row['sma_amount_withhold'];
		$prelease_withheld_amount		= $row['release_withheld_amount'];
		$pother_addition				= $row['other_addition'];
		$pother_statutory_deduction		= $row['other_statutory_deduction'];

		 $total_value_work_done = $sma_invoice_amount + $sma_variation_order_amt + $sma_variation_in_price;
	 $ptotal_value_work_done = $psma_invoice_amount + $psma_variation_order_amt + $psma_variation_in_price;
			
	 $gross_payable = $total_value_work_done + ($sma_material_advance + $sma_release_retention_money + $release_withheld_amount + $other_addition);
	 $pgross_payable = $ptotal_value_work_done + ($psma_material_advance + $psma_release_retention_money + $prelease_withheld_amount + $pother_addition);
    			
	 $gross_total_payable = $gross_payable - ($net_advance + $sma_deduct_retention_money + $sma_amount_withhold + $sma_variation_due_to_arbitratioon + $sma_deduction_liquidated_damage + $sma_other_deduction);
	 $pgross_total_payable = $pgross_payable - ($pnet_advance + $psma_deduct_retention_money + $psma_amount_withhold + $psma_variation_due_to_arbitratioon + $psma_deduction_liquidated_damage + $psma_other_deduction);
    			
	 $nett_value_cert = $gross_total_payable - ($sma_deduction_work_contract_tax + $other_statutory_deduction);
     $pnett_value_cert = $pgross_total_payable - ($psma_deduction_work_contract_tax + $pother_statutory_deduction);
     
	 $nett_amount_payable_cert = $nett_value_cert - $deduct_amount_certified_earlier;
	 $pnett_amount_payable_cert = $pnett_value_cert - $pdeduct_amount_certified_earlier;
	 
	 $net_amount = $nett_amount_payable_cert+$pnett_amount_payable_cert;
//echo $net_amount;
		
	$approver_1 		= $row['approver_1'];
	$approver_2 		= $row['approver_2'];
	$approver_3 		= $row['approver_3'];
	$approver_4 		= $row['approver_4'];
	$approver_5 		= $row['approver_5'];
	$approver_6 		= $row['approver_6'];
	$approver_7 		= $row['approver_7'];
	$approver_8 		= $row['approver_8'];

	$approver_1_status 	= $row['approver_1_status'];
	$approver_2_status 	= $row['approver_2_status'];
	$approver_3_status 	= $row['approver_3_status'];
	$approver_4_status 	= $row['approver_4_status'];	
	$approver_5_status 	= $row['approver_5_status'];
	$approver_6_status 	= $row['approver_6_status'];
	$approver_7_status 	= $row['approver_7_status'];
	$approver_8_status 	= $row['approver_8_status'];
	
		
?>

    <section class="content-header">
        <h1>
            IPC
            <small>Edit</small>
			<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
			</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">IPC</a></li>
        </ol>
    </section>
		
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
            
		<!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box-body">
            <!-- form start -->
            <form class="form-horizontal" action="ipc.php?sub=edit" method="post" enctype="multipart/form-data">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						
						<span class="pull-right"><h4 style="color:red;"><b><?php echo $row['status'];?></b></h4> </span>
						
						<span class="pull-right"><a href="<?php echo $baseurl . $modulePath .'ipc.php?sub=list'?>" class="btn btn-danger" >Back</a>&nbsp;&nbsp;&nbsp;</span>
		                
						<input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<input type="hidden" name="status" id="statuS" value="<?php echo $row['status'];?>" >						
							<?php 
								
								$_SESSION['sma_comp_id'] = $row['sma_comp_id'];
								$_SESSION['ipc_id'] 	= $id;
								$_SESSION['status']  = $status;

							?>
						<?php
						if ($_GET['active']){
							$active = $_GET['active'];
							$active_1 = ' ';
						}
						else
						{
							$active_1 = 'active';
						}
						?>
						
					<ul class="nav nav-tabs">
                     
						<li class="<?php echo $active_1;?>"><a href="#tab_1" data-toggle="tab" id="first_tab" >IPC</a></li>
                        <li><a href="#tab_2" data-toggle="tab" id="second_tab">Documents</a></li>
						<li><a href="#tab_3" data-toggle="tab" class="btn btn-info" id="three_tab" >Workflow History</a></li>
						<li><a href="ipc_prn.php?sub=pdf&id=<?php echo $row['id'];?>&comp_id=<?php echo $row['sma_comp_id'];?>&r=1" class="btn btn-success"  target="_blank" >View </a></li>
                    </ul>
					<div class="tab-content">
						<div class="tab-pane <?php echo $active_1;?>" id="tab_1">
						
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Company</label>
							<div class="col-md-3">
								<select class="form-control" name="sma_comp_id" id="sma_companY" autocomplete="off" <?php echo $readonly; ?> required>
									<option value=""> Select </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['sma_comp_id'] == $r2['comp_id'])?'selected="selected"':'';?> > <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<?php
								$party_id_doc   = $row['sma_vendor_id'];
								$company_id		= $row['sma_comp_id'];
							?>

							<label class="col-lg-1 control-label">Vendor </label>
							<div class="col-md-3">
								<select class="form-control" name="sma_vendor_id" id="sma_vendor_id" autocomplete="off" onchange="getpono(this.value); getsuppno(this.value) " <?php echo $readonly; ?> >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['sma_vendor_id'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['party_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<?php 	
								
								
								$ipc_date	= date('d-m-Y', strtotime($row['ipc_date']));
								if($ipc_date == '01-01-1970'){
									$ipc_date	='';
								}
							?>		
							<label class="col-lg-1 control-label">Date </label>
							<div class="col-md-2">
                                 <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                     <div class="input-group-addon">
                                          <i class="fa fa-calendar-alt"></i>
                                     </div>
                                     <input type="text" class="form-control" id="prDate"  name="ipc_date" placeholder="dd/mm/yyyy" readonly
                                               value="<?php echo $ipc_date;?>" <?php echo $readonly; ?> >
                                 </div>
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">PO Number</label>
							<div class="col-md-3">
							<span id="getpono">
								<select class="form-control select2" name="sma_po_no" id="sma_po_no" autocomplete="off" <?php echo $readonly; ?> required>
									<option value=""> Select </option>
										<?php $sql = "select * from sma_purchase_order  order by po_number "; //where status = 'Completed'
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ 
											$trans_type	= $r2['trans_type'];
											$po_number 	= $r2['po_number'];
											$po_rev		= $r2['po_rev'];
											if($po_rev>0){
												$po_number = $po_number.'-'.$po_rev;
											}
										?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['sma_po_no'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $po_number;?></option>
										<?php } ?>
								</select>
							</span>
								<input type="hidden" id="trans_type" value="<?php echo $trans_type;?>">
							</div>
						
							<label class="col-lg-2 control-label">PO Amount</label>
							<div class="col-md-2">
							<span id="getpoamt">
								<input type="text" class="form-control" id="sma_po_amount" name="sma_po_amount" autocomplete="off" <?php echo $readonly; ?> style="text-align:right;" value="<?php echo $row['sma_po_amount'];?>">
							</span>	
							</div>
						
							
						</div>
						<?php
						
							$vendor_id = $row['sma_vendor_id'];
							$sma_po_no = $row['sma_po_no'];
								
							$inv_checked ='';
							$adv_checked ='';
							$prev_checked ='';
							$ret_checked ='';
							$sma_inv_adv = $row['sma_inv_adv'];
						
							if($sma_inv_adv=='I'){
								$inv_checked = "CHECKED";
								$required  = "required";
							}
							else if($sma_inv_adv=='A'){
								$adv_checked = "CHECKED";
								$required  = '';
							}
							else if($sma_inv_adv=='P'){
								$prev_checked = "CHECKED";
								$required  = '';
							}
							else if($sma_inv_adv=='R'){
								$ret_checked = "CHECKED";
								$required  = '';
							}
							
						?>
						
						<input type="hidden" id="sma_inv_ADV" value="<?php echo $sma_inv_adv; ?>">
						
						<div class="form-group">
							<label class="col-lg-1 control-label">&nbsp;</label>
							<div class="col-md-4">
							
								<label class="control-label" style="text-align:center;">Against</label>		<br>
								
								<input type="radio" id="sma_inv_adv" name="sma_inv_adv" onclick="javascript:brd();"  <?php echo $inv_checked;?> value="I"> Invoice &nbsp;
								<input type="radio" id="sma_inv_adv" name="sma_inv_adv"  onclick="javascript:brd();"   <?php echo $adv_checked;?> value="A"> Advance &nbsp;
								<input type="radio" id="sma_inv_adv" name="sma_inv_adv"  onclick="javascript:brd();"  <?php echo $ret_checked;?> value="R"> Retention &nbsp;
								<input type="radio" id="sma_inv_adv" name="sma_inv_adv" onclick="javascript:brd();"   <?php echo $prev_checked;?> value="P"> Previous 
							</div>
							
							<?php //$r2['supplier_invoice_no'] id not in(select sma_invoice_no from sma_ipc ) or //where status = 'Completed'
		
							?>
							
		
							<div class="col-md-3">
							<label class="control-label" >Supplier Invoice Srno.<span style="color:red;">**</span> </label>
							<span id="getsuppno">
								<select class="form-control" name="sma_invoice_no" id="sma_invoice_NO" autocomplete="off" <?php echo $readonly; echo $required; ?> onchange = "getsuppamt(this.value)"  >
									<option value=""> Select </option>
									<?php 
										if($sma_inv_adv=='A'){
									?>
										<option value="0" <?php echo ( $row['sma_invoice_no'] == '0' )?'selected="selected"':'';?>> For Advance </option>
									<?php }	?>	
										<?php //$sql = "select * from sma_supplier_invoice where id = '$si_id' order by id desc " ;
											  $sql = " SELECT * FROM sma_supplier_invoice where suplier_name = '$vendor_id' and our_po_ref_no = '$sma_po_no'  ORDER BY invoice_date ASC "; //and id not in ( SELECT sma_invoice_no FROM `sma_ipc` )
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['sma_invoice_no'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['id'];?></option>
										<?php } ?>
								</select>
							</span>
							</div>
							
							<?php 
								$si_id = $row['sma_invoice_no'];
								$sql = "select * from sma_supplier_invoice where id = '$si_id' ";
								//echo $sql;
								$q2 	  = mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_array($q2)){ 
									$sma_invoice_no 	= $r2['supplier_invoice_no'];	
								}
							?>
										
							<span id="getsuppamt">
							<div class="col-md-2">
								<label class="control-label">Invoice No.</label>
								<input type="text" class="form-control" <?php echo $readonly; ?> value="<?php echo $sma_invoice_no;?>">
							</div>
							<div class="col-md-2">
								<label class="control-label">Invoice.Amt</label>
							
								<input type="text" class="form-control" id="sma_invoice_amount" name="sma_invoice_amount" style="text-align:right;" <?php echo $readonly; ?> autocomplete="off" value="<?php echo $row['sma_invoice_amount'];?>">
							
							</div>
							
							</span>	
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Variation Order Amount</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_variation_order_amt" name="sma_variation_order_amt" <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="<?php echo $row['sma_variation_order_amt'];?>">
							</div>
						
							<label class="col-lg-3 control-label">Variation in Price(VOP)</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_variation_in_price" name="sma_variation_in_price" <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="<?php echo $row['sma_variation_in_price'];?>">
							</div>

								<?php 
								
									$our_po_ref_no = $row['sma_po_no'];
									$invoice_no = $row['sma_invoice_no'];
									
									$sql = "SELECT * FROM sma_purchase_order where id = '$our_po_ref_no' or po_number = '$our_po_ref_no'  ";
									$q2  = mysqli_query($con, $sql);
									$r2  = mysqli_fetch_array($q2);
									$po_id = $r2['id'];
									$approval_memo_ref = $r2['approval_memo_ref'];
									$location	 	   = $r2['location'];
									$comp_id		   = $r2['project'];
									
									$sql = "SELECT * FROM sma_approval_memo where id = '$approval_memo_ref'  ";
									$q2  = mysqli_query($con, $sql);
									$r2  = mysqli_fetch_array($q2);
									$ap_id = $r2['id'];
									
								//	$sql = "select * from payment_details where supp_id = '$id' ";
								//	$q2  = mysqli_query($con, $sql);
								//	$r2  = mysqli_fetch_array($q2);
								//	$py_id = $r2['id'];
									
									//	$po_id = $po_id;
									//	$baseurl_po = $baseurl . "purchase_order/edit.php?sub=edit&id=$po_id";
										
									//	$ap_id = $ap_id;
									//	$baseurl_ap = $baseurl . "approval/edit.php?sub=edit&id=$ap_id";
									
										//$baseurl_py = $baseurl . "payment/edit.php?sub=edit&id=$py_id";
									
										$po_id = $po_id;
										$baseurl_po = $baseurl . "purchase_order/pur_order_prn.php?sub=pdf&id=$po_id&comp_id=$comp_id&location=$location";
										
										$ap_id = $ap_id;
										$company_id = $row['sma_comp_id'];
										$baseurl_ap = $baseurl . "approval/approval_notes_prn.php?sub=pdf&id=$ap_id&comp_id=$company_id;&r=1";
										
										$po_id = $po_id;
										$baseurl_powf = $baseurl . "purchase_order/po_wf_prn.php?sub=pdf&id=$po_id&comp_id=$comp_id&location=$location";
										
										$supp_id = $row['sma_invoice_no'];
										$baseurl_si = $baseurl . "supp_invoice/edit.php?sub=edit&id=$supp_id";
									
									?>
									<div class="col-md-3">
										<label class="control-label">Document</label><br>
										<a href="<?php echo $baseurl_ap; ?>" target ="_blank"<span class="label label-danger">Approval Notes</span></a>&nbsp;
										<a href="<?php echo $baseurl_po; ?>" target ="_blank"><span class="label label-success">Purchase Order</span></a>&nbsp;
										<a href="<?php echo $baseurl_si;?>" target="_blank"><span class="label label-warning">Invoice</span></a>
									</div>

						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Material Advance </label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_material_advance" name="sma_material_advance" <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="<?php echo $row['sma_material_advance'];?>">
							</div>
							<label class="col-lg-5 control-label">&nbsp;</label>
						
						<!--	<label class="col-lg-3 control-label">Material Advance Recovery</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_material_advance_recovery" name="sma_material_advance_recovery" <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="<?php echo $row['sma_material_advance_recovery'];?>">
							</div>-->
							
									<div class="col-md-2">
								<?php	
									$sql = "SELECT * FROM sma_grn_srn where our_po_ref_no = '$po_id' ";//and supplier_invoice_no in (SELECT supplier_invoice_no FROM `sma_supplier_invoice` where id = '$invoice_no' )
									$q2  = mysqli_query($con, $sql);
									while($r2  = mysqli_fetch_array($q2)){
										$srn_id = $r2['id'];
										$company_id = $row['sma_comp_id'];
										$baseurl_srn = $baseurl . "grnsrn/grnsrn_prn.php?sub=pdf&id=$srn_id&comp_id=$company_id;&r=1";
									?>
										<a href="<?php echo $baseurl_srn; ?>" target ="_blank"><span class="label label-info">GRN-<?php echo $srn_id;?></span></a>&nbsp;
									<?php
									}
									?>
									
										<a href="<?php echo $baseurl_powf;?>" target="_blank"><span class="label label-info">PO Workflow</span></a>
									</div>
						</div>
						
						<div class="form-group">
							
							<label class="col-lg-2 control-label">Deduct Retention Money</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_deduct_retention_money" name="sma_deduct_retention_money" <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="<?php echo $row['sma_deduct_retention_money'];?>">
							</div>
						
							<!--<label class="col-lg-3 control-label">Release of Retention Money.</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_release_retention_money" name="sma_release_retention_money" <?php echo $readonly; ?> style="text-align:right;" readonly autocomplete="off" value="<?php echo $row['sma_release_retention_money'];?>">
							</div>
							-->
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Variation due to Arbitration/ Claims or disputes</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_variation_due_to_arbitratioon" name="sma_variation_due_to_arbitratioon" <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="<?php echo $row['sma_variation_due_to_arbitratioon'];?>">
							</div>
						
							<label class="col-lg-3 control-label">Deduction Work contract Tax</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_deduction_work_contract_tax" name="sma_deduction_work_contract_tax" <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="<?php echo $row['sma_deduction_work_contract_tax'];?>">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Deduction Liquidated Damage</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_deduction_liquidated_damage" name="sma_deduction_liquidated_damage" <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="<?php echo $row['sma_deduction_liquidated_damage'];?>">
							</div>
							
							<label class="col-lg-2 control-label">Amount Withhold</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_amount_withhold" name="sma_amount_withhold" <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="<?php echo $row['sma_amount_withhold'];?>">
							</div>
							
							<label class="col-lg-2 control-label">Release of Withheld Amount</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="release_withheld_amount" name="release_withheld_amount" <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="<?php echo $row['release_withheld_amount'];?>">
							</div>
						
						</div>
						
						<div class="form-group">
						 
 						    <label class="col-lg-2 control-label">Other Deduction </label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_other_deduction" name="sma_other_deduction" autocomplete="off" <?php echo $readonly; ?> style="text-align:right;" value="<?php echo $row['sma_other_deduction'];?>">
							</div>
						
							<label class="col-lg-1 control-label"> Description</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="sma_other_deduction_desc" name="sma_other_deduction_desc" autocomplete="off" <?php echo $readonly; ?> value="<?php echo $row['sma_other_deduction_desc'];?>">
							</div>
						</div>
						
						<div class="form-group">
							
							<label class="col-lg-2 control-label">Other Addition </label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="other_addition" name="other_addition" autocomplete="off" <?php echo $readonly; ?>  style="text-align:right;" value="<?php echo $row['other_addition'];?>">
							</div>
						
							<label class="col-lg-1 control-label"> Description</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="other_addition_desc" name="other_addition_desc" <?php echo $readonly; ?>  autocomplete="off"  value="<?php echo $row['other_addition_other'];?>">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Other Statutory Deduction </label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="other_statutory_deduction" name="other_statutory_deduction" <?php echo $readonly; ?>  autocomplete="off" style="text-align:right;" value="<?php echo $row['other_statutory_deduction'];?>">
							</div>
						
							<label class="col-lg-1 control-label"> Description</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="other_statutory_deduction_desc" name="other_statutory_deduction_desc" <?php echo $readonly; ?>  autocomplete="off"  value="<?php echo $row['other_statutory_deduction_desc'];?>">
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-12">
                                <div class="box-header"><span class="box-title">Remarks</span></div>
                                <div class="box-body">
                                    <textarea class="form-control" id="reason2" name="remarks"  <?php echo $readonly; ?>
                                                 placeholder="Enter text ..."><?php echo  stripslashes($row['remarks']);?></textarea>
                                </div>
                            </div>
						</div>
							
							<div class="box-footer">
								<div class="col-sm-6">
									
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
									 <a href="#tab_2" class="btn btn-primary" data-toggle="tab" onclick="$('#second_tab').trigger('click')" >Next</a>
								</div>
							</div>
							
				</div>
					
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
						
						<div class="tab-pane " id="tab_2" >
						
							<?php	
							//if($status =='Completed' || $user =='Admin'){
							$sql = "SELECT count(*) as cnt FROM `my_documents_files` a, sma_document_type b , sma_party_mst c, dms_inward d 
									where b.id = a.doc_Type and d.sent_by_user_type = 'P' and d.sent_by_user_vendor = c.id 
									and a.reference_Id = d.inward_no and c.id = '$party_id_doc' and d.company_for = '$company_id' "; //  limit 0,5 
						//echo $sql;			
							$res = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$cn1 = mysqli_fetch_array($res);
							$cnt = $cn1['cnt'];
						//$cnt=1;	
						if($cnt>0){
								
						?>  
    						
							<div class="col-sm-7" >&nbsp;</div>
							<div class="col-sm-5" >		
								<span style="font-size:18px;color:white;" class="btn btn-info" >Select Document from DMS </span>&nbsp;&nbsp;
								<span > &nbsp;&nbsp;</span>
								<input type ="checkbox" id="partyDoc" name="partydoc" value='Y' onclick="getpartydoc(this.value)" >
							</div>	
								<input type ="hidden" id="party_id_doc" name="party_id_doc" value="<?php echo $party_id_doc; ?>" >
								<input type ="hidden" id="company_idd_doc" name="company_idd_doc" value="<?php echo $company_id; ?>" >
						<?php } ?>
								
								
							 <?php
                              $sql = "SELECT * FROM file_uploads WHERE module = 'IP' AND reference_id = " . $ipc_id;
                              $docResults = mysqli_query($con, $sql);
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th>Document Type</th>
                                          <th>Description</th>
										  <th>Document Name</th>
                                          <th>Action</th>
                                          
                                      </tr>
                                      </thead>
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
				                                  $delDocUrl = "deldoc.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
													
													$doc_type = $docRow['doc_type'];
													$sql="SELECT * FROM sma_document_type where id ='$doc_type' ";
													$rs = mysqli_query($con, $sql);
													$rw = mysqli_fetch_array($rs);
													$document = $rw['document'];
													
												  ?>
                                          <tr>
                                              <td><?php echo $document; ?></td>
                                              <td><?php echo $docRow['doc_desc'] ?></td>
                                              <td><a target="_blank" href="<?php echo $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
										<?php if (empty($readonly)){ ?>
											<td><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
                                        <?php } ?>
                                          </tr>
				                              <?php
				                              }
				                              ?>
                                      </tbody>
                                      
                                  </table>
								  
						
						<span id="gegpartyDoc">
							
						</span>
						
						<?php //if (empty($readonly)){ ?>		  
    						<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr> 
										<td></td>
										<td><label class="col-sm-1 control-label">Document</label>
                                            <select class="form-control col-sm-2 doctype" name="doctype[]" >
                                                <option value="0">Select</option>
												<?php
												$sql="SELECT * FROM sma_document_type where 1  ORDER BY document ASC";
												$rs = mysqli_query($con, $sql);
												echo mysqli_error($con);
												while($rw = mysqli_fetch_array($rs)){
												?>
													<option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['document'] ?></option>
												<?php } ?>	
												
                                            </select>
										</td>
										<td><label class="col-sm-1 control-label">Description</label>
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea>
										</td>
										<td><label class="control-label col-sm-3">Attachment</label><br>
											<input type="file" name="fudoc[]" class="docfile">
										</td>
                                         <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
                                    </tr>  
                               </table>  
                            <!--   <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />  -->
							</div>
						<?php
				          //  }
				        ?>
                                  	

<?php
$opt = '';	
$sql="SELECT * FROM sma_document_type where 1 ORDER BY document ASC";
$rs = mysqli_query($con, $sql);
echo mysqli_error($con);
while($rw = mysqli_fetch_array($rs)){
	$did 		= $rw['id'];
	$ddocument 	= $rw['document'];
	$opt .= "<option value='" . $did ."' > ".$ddocument."</option>";
 }

?>

<input type="hidden"  value="<?php echo $opt ?>" name="opt" id="opt">
										
						
							<div class="box-footer">
								<div class="col-sm-6">
									 <a href="#tab_1" class="btn btn-primary" data-toggle="tab" onclick="$('#first_tab').trigger('click')" >Previous</a>					
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
								<a href="#tab_3" class="btn btn-primary" data-toggle="tab" onclick="$('#three_tab').trigger('click')" >Next</a>			
									<span>&nbsp;&nbsp;</span>
								</div>
							</div>

							
						<span id="predit"></span>
						<div class="box-footer">	
							<div class="col-sm-6">
					<?php 	
						if($del=='Y'){ ?>
							
								
									<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Make to Draft</a>	
								
					<?php
						}
						else if ( $status != 'Draft' && $user == 'Admin' ){	
					?>
							
									<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Make to Draft</a>
								
					<?php	
						}
					?>	
					<?php		
								if ($user=='Admin' || $status == 'Draft'){
							?>		
									<!--<a href="<?php echo $baseurl.$modulePath."ipc.php?sub=delete&id=$did";?>" class="btn btn-danger" >Delete</a>
									<span>&nbsp;&nbsp;</span>-->
									
									<a href="#deleteAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#deleteAuthority">Delete</a>		
									
							<?php } ?>		
									
						</div>
						
					<?php	
						if($del!='Y'){ ?>
								
								<?php $did = $_GET['id']; ?>
								<div class="col-sm-6 text-right">
									<?php		
									$role			= $_SESSION['role'];
									$userid   	= $_SESSION['usrid'];	
								
								$approver_flag='';
							if( $status != 'Draft' ){
//echo $userid . ' ' . 	$approver_5 . ' ' . $approver_1_status. "<BR>";								
								$approver_flag='';
								if( $userid == $approver_1 && $approver_1_status=='Submitted' || 
									$userid == $approver_2 && $approver_2_status=='Submitted' || 
									$userid == $approver_3 && $approver_3_status=='Submitted' ||
									$userid == $approver_4 && $approver_4_status=='Submitted' ||
									$userid == $approver_5 && $approver_5_status=='Submitted' ||
									$userid == $approver_6 && $approver_6_status=='Submitted' ||
									$userid == $approver_7 && $approver_7_status=='Submitted' ||
									$userid == $approver_8 && $approver_8_status=='Submitted' ){
				
									if($approver_1_status=='Submitted' 
										&& empty($approver_2_status) && empty($approver_3_status) 
										&& empty($approver_4_status) && empty($approver_5_status)
										&& empty($approver_6_status) && empty($approver_7_status)
										&& empty($approver_8_status) ){
										$approver_flag='Y';
									}

									if($approver_1_status=='Approved' && $approver_2_status=='Submitted'
										&& empty($approver_3_status) && empty($approver_4_status) 
										&& empty($approver_5_status) && empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Submitted' && empty($approver_4_status)
										&& empty($approver_5_status) && empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Submitted'
										&& empty($approver_5_status) && empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Submitted'
										&& empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Submitted'
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Approved'
										&& $approver_7_status=='Submitted' && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Approved'
										&& $approver_7_status=='Approved'
										&& $approver_8_status=='Submitted' ){
										$approver_flag='Y';
									}
									
								}
								
										if ($status_a!='Approved'){
									?>	
											<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">Approve </a>
											<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
											<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
									
									<?php
											}
										}	
									  }
									?>
									
							<?php		
							
								if ($status != 'Submitted' && $status != 'Completed'){
									$c_role  = '';
									$userid   	= $_SESSION['usrid'];											
									$s1   = "SELECT a.id, c.role as role, b.role as role_id, a.create_by, b.username FROM `workflow_history` a, sma_user b, sma_role c where doc_type = 'IP' and doc_id = '$ipc_id'  and a.create_by = b.id and b.role = c.id order by a.id desc";
									$res  = mysqli_query($con, $s1);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res);
									$c_role  = $r1['role'];
									$create_by = $r1['create_by'];
						//echo $s1."<br>";
						//echo $c_role. ' ' . $create_by;
									
								if($status == 'Draft'){
							?>
									<span class='hidesend'>	
										<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send " >
									</span>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<?php
								} 
									
							?>
									<input class="btn btn-primary" type="submit" onclick="validate()" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
							
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									<?php 
										$baseurl1 = $baseurl.$modulePath."ipc.php?sub=list&reset=1";
									?>
									<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Back</a>
									<span>&nbsp;&nbsp;</span>
				
				
							<?php if ($sma_inv_adv !='P'){ ?>
									<span>&nbsp;&nbsp;</span>
									<?php if( ($role=='Maker' && $c_role =='Maker' && $create_by == $userid ) || ($role=='Maker' && $approval_status=='Rejected') || ($role=='Maker' && $c_role =='CXO' && $status=='Draft' ) ){ ?>
										<span>&nbsp;&nbsp;</span>
										<a href="#approvalAuthority" class="btn btn-primary" data-toggle="modal" data-mode="Submit" data-target="#approvalAuthority">Submit</a>
									<?php 
										}
									}
									
								}
									
							?>
							
								</div>
								
							

					
					</div>
					
					<span id="getapprover">	
					
					<?php 
						if( $status == 'Draft' ){
						?>
						
							<div class="box-footer">
								<div class="col-sm-2">
									<label class="control-label">&nbsp;</label>
								</div>
							<?php	
							
								if(!empty($approver_1)){
									$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_1 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm .= $rw['role'];
									}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 1</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_1" name="approver_1"   >
                                        <?php
										$sql 	= " select * from sma_user where id = $approver_1 ";
										$rs 	= mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_1 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
							<?php }	
							
								if(!empty($approver_2)){
									$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_2 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm .= $rw['role'];
									}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_2" name="approver_2" >
                                        <?php
										$sql = " select * from sma_user where id = $approver_2 ";
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_2 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php }
							
								if(!empty($approver_3)){
									$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_3 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm .= $rw['role'];
									}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 3</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_3" name="approver_3" >
                                        <?php
										$sql = " select * from sma_user where id = $approver_3 ";
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_3 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } 
								if(!empty($approver_4)){
									$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_4 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm .= $rw['role'];
									}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 4</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_4" name="approver_4" >
                                        <?php
										$sql = " select * from sma_user where id = $approver_4 ";
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_4 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>	
							<?php if(!empty($approver_5)){ 
									$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_5 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm .= $rw['role'];
									}												
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 5</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_5" name="approver_5" >
                                        <?php
										$sql = " select * from sma_user where id = $approver_5 ";
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_5 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>	
							<?php if(!empty($approver_6)){ 
									$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_6 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm .= $rw['role'];
									}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 6</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_6" name="approver_6" >
                                        <?php
										$sql = " select * from sma_user where id = $approver_6 ";
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_6 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>	
							
							<?php if(!empty($approver_7)){ 
										$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_7 ";
										$rs 	= mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
											$rolenm .= $rw['role'];
										}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 7</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_7" name="approver_7" >
                                        <?php
										$sql = " select * from sma_user where id = $approver_7 ";
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_7 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>	
							
							<?php if(!empty($approver_8)){ 
										$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_8 ";
										$rs 	= mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
											$rolenm .= $rw['role'];
										}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 8</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_8" name="approver_8" >
                                        <?php
										$sql = " select * from sma_user where id = $approver_8 ";
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_8 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							
							
							<?php } ?>	
							
								<BR>
								
							</div>
							
							
						<?php } ?>		
						
						</span>
						
			</div>
						
						
						
						<div class="tab-pane" id="tab_3">
							
							<div class="modal-header" >
								
								<?php 
									
									
									$srno = $ipc_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'IP' order by id ";
							//echo $s1;		
									$res  = mysqli_query($con, $s1);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res);
									$create_by		= $r1['create_by'];
									$create_date	= date('d-m-Y', strtotime($r1['create_date']));
									
									$sl="SELECT * FROM sma_user where id = '$create_by' ";
									$r3 = mysqli_query($con, $sl);
									$rw = mysqli_fetch_array($r3);
									$create_by = $rw['first_name'].' '.$rw['last_name'];
					
								?>			
								<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $srno;?> <?php echo "&nbsp;&nbsp; Created By: ".$create_by; ?> <?php echo "&nbsp;&nbsp; Dated: ".$create_date; ?>
								 
								</span>
												
												
								 
								 
							</div>
							

							<div class="modal-body1" >
								<section class="content2">
								<div class="row1">
								   
										<table id="prtable" class="table table-bordered table-striped">
											<thead>
											<tr>
											  <th></th>	
											  
											  <th>Dated</th>
											  <th>By User</th>
											  <th>Decision</th>
											  <th>Send Dated</th>
											  <th>To User</th>
											  <th>Role</th>
											  <th>remarks</th>
											  
											</tr>
											</thead>
											<tbody>
										<?php
										//style="position:relative;overflow-y:auto;min-width: 600px; max-height: 500px;padding: 15px;"
											//$srno = $rid;
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'IP' order by id desc";
									//	echo $s1;
											$res  = mysqli_query($con, $s1);
											echo mysqli_error($con);
											while($r1 = mysqli_fetch_array($res)){
												$id 				= $r1['id'];
												
												$status				= $r1['status'];
												$reviewed_by 		= $r1['reviewed_by'];
												$approved 			= $r1['approved'];
												$approved_date		= date('d-m-Y h:i:sa', strtotime($r1['approved_date']));
												$remarks 			= $r1['remarks'];
												
												
												$s2="SELECT * FROM sma_user where id = '$reviewed_by' ";
										//echo $s2. "<BR>";
												$r3 = mysqli_query($con, $s2);
												$rw1 = mysqli_fetch_array($r3);
												$reviewed_by = $rw1['username'];
										//echo $reviewed_by . " <<<<<BR>";
												
												$role = $rw1['role'];

												$sl="SELECT * FROM sma_role where id = '$role' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$role = $rw['role'];
												
												$create_by		= $r1['create_by'];
												$create_date	= date('d-m-Y h:i:sa', strtotime($r1['create_date']));
												
												$sl="SELECT * FROM sma_user where id = '$create_by' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$create_by = $rw['username'];
												
										?>      
											<tr>
												<td width="1%"><input type="hidden" value="<?php echo $id; ?>" ></td>
												<td width="10%" style="text-align:left;"><?php echo $create_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $create_by; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $status; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $approved_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $reviewed_by;?></td>
												<td width="10%" style="text-align:left;"><?php echo $role;?></td>
												<td width="10%" style="text-align:left;"><?php echo $remarks;?></td>
											</tr>
										<?php	}	?>	
											</tbody>
										</table>
										
							
										
									</div>
								</section>
							  </div>
						
							<div class="box-footer">
								<div class="col-sm-6">
									 <a href="#tab_2" class="btn btn-primary" data-toggle="tab" onclick="$('#second_tab').trigger('click')" >Previous</a>					
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6">
									<span>&nbsp;&nbsp;</span>
								</div>
								
							</div>
								
						</div>
						
					</div>
					
                    <!--    <div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="ipc.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->
                        
                    </fieldset>
				</div>	
            </form>
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
    </div>
  </div>
</section>
      
<?php } 	?>



<!--Maker Workflow Popup-->

<div class="modal fade" id="makerAuthority" role="dialog" aria-labelledby="makerAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="makerAuthority">Send Back To Maker </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    
								<form class="form-horizontal">
                                        
										<?php   
											
											$ipc_id = $_SESSION['ipc_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											$user_department = $_SESSION['user_department'];
											$sma_comp_id		 = $_SESSION['sma_comp_id'];
											
										?>
										
										<input type="hidden" name="ipc_id" id="ipc_idM" value="<?php echo $ipc_id; ?>" >
										<input type="hidden" id="modeM" name="mode" value='Checker'>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksM"></textarea>
											</div>
										</div>
									
								</form>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										<button type="button" class="btn btn-primary" id="submitMaker">Submit</button>
									</div>
			
                                </div>
                            </div>		
			</section>
		</div>
    </div>
  </div>
</div>

<!--Maker Workflow Popup End -->


<!--Make to DraftPopup-->

<div class="modal fade" id="makeDraftAuthority" role="dialog" aria-labelledby="makeDraftAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="makeDraftAuthority">Do you want to Make Draft? </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">                                        
										<?php
										
											$ipc_id 	= $_SESSION['ipc_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											$company= $_SESSION['company'];
											
										?>
										<input type="hidden" name="ipc_id" id="ipc_idD" value="<?php echo $ipc_id; ?>" >
										<input type="hidden" id="modeD" name="mode" value='Accept'>
										<input type="hidden" id="approverD" name="approver" value='<?php echo $approver;?>'>
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusD" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksD"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitDraft">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Make to DraftPopup-->


<!--Delete  Popup-->

<div class="modal fade" id="deleteAuthority" role="dialog" aria-labelledby="deleteAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="deleteAuthority">Do you want to Delete? </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">                                        
										<?php
										
											$ipc_id 	= $_SESSION['ipc_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											$company= $_SESSION['company'];
											
										?>
										<input type="hidden" name="ipc_id" id="ipc_idZ" value="<?php echo $ipc_id; ?>" >
										<input type="hidden" id="modeZ" name="mode" value='Accept'>
										<input type="hidden" id="approverZ" name="approver" value='<?php echo $approver;?>'>
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusZ" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksZ"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitDelete">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Delete Popup End -->

<!--Checker Workflow Popup-->

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
                                        
										<?php   
											$ipc_id	= $_SESSION['ipc_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											$user_department = $_SESSION['user_department'];
											$sma_comp_id		 = $_SESSION['sma_comp_id'];
											
										?>
										
										<input type="hidden" name="ipc_id" id="ipc_idE" value="<?php echo $ipc_id; ?>" >
										<input type="hidden" id="modeC" name="mode" value='Checker'>
										
										<div class="form-group col-md-12">
                                        
											<label for="approver" class="col-sm-4 control-label">User Name</label>
                                            <div class="col-sm-7">
                                                <span id="getuser">
													<select class="form-control select2123" id="approverE" name="approver" required>
													    <option value="">Select</option>
														<?php
														    $sql="SELECT * FROM sma_user where FIND_IN_SET('$sma_comp_id', company_id)<>0 and role in ( select id from sma_role where role = 'Checker' )  ORDER BY first_name ASC";
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
										
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusE" style="color:red;" readonly name="status" value="<?php echo $status ?>" >

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

<!--Approval Workflow Popup-->

<div class="modal fade" id="approvalAuthority" role="dialog" aria-labelledby="approvalAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="approvalAuthority">Remarks </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
										
											$ipc_id = $_SESSION['ipc_id'];
											$status = $_SESSION['status'];
											
										?>
										
										<input type="hidden" name="ipc_id" id="ipc_idE" value="<?php echo $ipc_id; ?>" >
										<input type="hidden" id="modeE" name="mode" value='Approve'>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksA"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitApprove">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Approval Workflow Popup End -->
	  

<!--Reject Workflow Popup-->

<div class="modal fade" id="rejectAuthority" role="dialog" aria-labelledby="rejectAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="rejectAuthority">Remarks </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$ipc_id 	= $_SESSION['ipc_id'];
											$status = $_SESSION['status'];
											
										?>
										
										<input type="hidden" name="ipc_id" id="ipc_idR" value="<?php echo $ipc_id; ?>" >
										<input type="hidden" id="modeR" name="mode" value='Reject'>
										<input type="hidden" id="statusR" name="status" value="<?php echo $status ?>">
										
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksR"></textarea>
											</div>
										</div>
									
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitReject">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Reject Workflow Popup End -->	  

<!-- For Document Attachment Start-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        
<script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){
			var opt =  document.getElementById('opt').value;
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><label class="col-sm-1 control-label">&nbsp;</label></td><td><select class="form-control select2 doctype" name="doctype[]"  ><option value="0">Select</option>'+opt+'</select></td><td><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td><input type="file" name="fudoc[]" class="docfile"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  
      $('#submit').click(function(){            
           $.ajax({  
                url:"name.php",  
                method:"POST",  
                data:$('#add_name').serialize(),  
                success:function(data)  
                {  
                     alert(data);  
                     $('#add_name')[0].reset();  
                }  
           });  
      });  
 });  
 </script>
 <!-- For Document Attachment End-->
 
<?php 	
	include("../footer.php");	
?>

<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>
<!-- InputMask -->
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.date.extensions.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.extensions.js" ?>"></script>

<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo $baseurl . "plugins/ckeditor/ckeditor.js" ?>"></script>

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(function () {
        $("#prtable").DataTable();
    });
</script>


<script>


   $("#submitDraft").on("click", function(e){
        var mode		 	=  $("#modeD").val();
		var ipc_id		 	=  $("#ipc_idD").val();
        var status 			=  $("#statusD").val();
		var remarks			=  $("#remarksD").val();
		
//alert(remarks +  ' ' + ipc_id + ' ' + st_flag);
	
		$('#makeDraftAuthority').modal('hide');
		var strURL = "py_draft_func.php";
		$.post(strURL,{ ipc_id:ipc_id,
						mode:mode,
						status:status,
						remarks:remarks},
						function(result){
		      $('#predit').html(result);
		});
	});

   $("#submitDelete").on("click", function(e){
        var mode		 	=  $("#modeZ").val();
		var ipc_id		 	=  $("#ipc_idZ").val();
        var status 			=  $("#statusZ").val();
		var remarks			=  $("#remarksZ").val();
		
//alert(remarks +  ' ' + ipc_id + ' ' + st_flag);
	
		$('#deleteAuthority').modal('hide');
		var strURL = "py_delete_func.php";
		$.post(strURL,{ ipc_id:ipc_id,
						mode:mode,
						status:status,
						remarks:remarks,
						mode:mode},
						function(result){
		      $('#predit').html(result);
		});
	});


   $("#submitMaker").on("click", function(e){
        var sub 			= 'sub11';
		var mode		 	=  $("#modeM").val();	
		var ipc_id		 	=  $("#ipc_idM").val();
		var remarks			=  $("#remarksM").val();

//	alert(sub + ' ' + mode + ' ' + approver + ' ' + status );
		 $('#makerAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ ipc_id:ipc_id,
						mode:mode,
						remarks:remarks,
						sub11:sub},
						function(result){
		      $('#predit').html(result);
		});
	});
	
	
   $("#submitChecker").on("click", function(e){
        var sub = 'sub10';
		var mode		 	=  $("#modeC").val();
		
		var ipc_id		 	=  $("#ipc_idE").val();
		var approver		=  $("#approverE option:selected").val();
        var status 			=  $("#statusE").val();
		var remarks			=  $("#remarksE").val();

//	alert(sub + ' ' + mode + ' ' + approver + ' ' + status );		 

		if(approver==''){
			alert("User Name should select...");
			return;
		}
	
		 $('#chekerAuthority').modal('hide');
				 
		var strURL = "app_func.php";
		$.post(strURL,{ ipc_id:ipc_id,
						mode:mode,
						approver:approver,
						status:status,
						remarks:remarks,
						sub10:sub},
						function(result){
		      $('#predit').html(result);
		});
	});
	
	
    $("#submitApprove").on("click", function(e){
        var sub = 'sub9';
		var mode		 	=  $("#modeE").val();
		
//		alert(sub + ' ' + mode);		 
		var ipc_id		 	=  $("#ipc_idE").val();
	    var status 			=  $("#statuS").val();
		var approver		=  $("#approverC").val();
		
		var company			= $("#sma_companY").val();
		
        var statusap		=  mode;
		var remarks			=  $("#remarksA").val();
		
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+ipc_id);
		 
		var strURL = "app_func.php";
		$.post(strURL,{ ipc_id:ipc_id,
						mode:mode,
						company:company,
						status:status,
						approver:approver,
						statusap:mode,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
		
		$('#approvalAuthority').modal('hide');
		
	});

		
    $("#submitReject").on("click", function(e){
        var sub = 'sub9';
		var mode		 	=  $("#modeR").val();
		
//		alert(sub + ' ' + mode);		 
		var ipc_id		 	=  $("#ipc_idR").val();
		
		var status 			=  $("#statusR").val();
		//var ipc_id			=  $("#iD").val();
		
		//var account_year	= $("#account_Year").val();
		var company			= $("#sma_companY").val();
		var budget_head_id	= $("#budget_head_Id").val();
		var budget_name		= $("#budget_Name").val();
		
        var statusap		=  mode;
		var remarks			=  $("#remarksR").val();
		
//alert(statusap+' #0# << '+status+' >> #1# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+ipc_id);

		 $('#rejectAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ ipc_id:ipc_id,
						mode:mode,
						company:company,
						status:status,
						statusap:mode,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
		
	});

</script>
	
<script>
function getpono(id){
    var sub = 'sub1';
	
	var comp_id		 	=  $("#sma_comp_iD").val();
	
//alert(id + ' <> ' + comp_id);
	var strURL = "ipc_func.php";
	$.post(strURL,{ sub1:sub,comp_id:comp_id,id:id},function(result){
			  $('#getpono').html(result);
		});
}


function getpoamt(id){
    var sub = 'sub2';
//alert(id);
	var strURL = "ipc_func.php";
	$.post(strURL,{ sub2:sub,id:id},function(result){
			  $('#getpoamt').html(result);
		});
}

		

function getsuppno(id){
    var sub = 'sub3';
	
	
	var sma_inv_advP 	=  $("#sma_inv_advP").val();
	var sma_inv_advA 	=  $("#sma_inv_advA").val();
	var sma_po_no		= $("#sma_po_no").val();
	var sma_vendor_id	= $("#sma_vendor_id").val();
	
//alert(sma_po_no);
	var strURL = "ipc_func.php";
	$.post(strURL,{ sub3:sub,sma_inv_advA:sma_inv_advA,sma_inv_advP:sma_inv_advP,sma_po_no:sma_po_no,id:sma_vendor_id},function(result){
			  $('#getsuppno').html(result);
		});
}

function getsuppamt(id){
    var sub = 'sub4';
//alert(id);
	var strURL = "ipc_func.php";
	$.post(strURL,{ sub4:sub,id:id},function(result){
			  $('#getsuppamt').html(result);
		});
}

$(document).ready(function () {
        $('.datepicker').datepicker({
            "format": 'd/M/Y',
            "autoclose": true
        });
        $('.select2').select2();
        CKEDITOR.replace('reason');
        CKEDITOR.replace('reason1');
        CKEDITOR.replace('reason2');
        CKEDITOR.replace('reason3');
        $("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
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

function validate(){
	
	
	var sma_inv_adv 	=  $("#sma_inv_ADV").val();
	var sma_invoice_no 	=  $("#sma_invoice_NO").val();
	//alert(sma_invoice_no+ ' ' + sma_inv_adv);
	
	if( sma_invoice_no=='' && sma_inv_adv=='I' ){
		alert('Please select Invoice Number...');
		return false;
	}
}	
	
	
 function brd() {alert($('[name="sma_inv_adv"]:checked').val());}
	
	

	function getpartydoc(id){

		var sub    = 'sub23';
		var id 	   = 'N';
		var checkBox = document.getElementById("partyDoc");
		if (checkBox.checked == true){
			var id	='Y';
		}	

		if(id=='Y'){
			var party_id_doc = document.getElementById("party_id_doc").value;
			var company_idd_doc = document.getElementById("company_idd_doc").value;
			
			
		//alert(id + ' ' + sub + ' ' + party_id_doc);
			var strURL = "app_func.php";
			$.post(strURL,{id:id,sub23:sub,party_id_doc:party_id_doc,company_idd_doc:company_idd_doc},function(result){
				  $('#gegpartyDoc').html(result);
			});
		}
		else {
			$('#gegpartyDoc').html("");
		}	

	}
	
		function getapprover(){
			
		var company_id    	= document.getElementById("sma_companY").value;
		var checker_value   = document.getElementById("sma_invoice_amount").value;
		var checker_valuepo = document.getElementById("sma_po_amount").value;
		
		var trans_type    	= document.getElementById("trans_type").value;
		
		var sub = 'sub34';
//alert(sub + ' ' + company_id + ' ' + checker_value + ' ' + trans_type);
		$('.hidesend').hide();
		
		var strURL = "app_func.php";
		$.post(strURL,{company_id:company_id,checker_value:checker_value,checker_valuepo:checker_valuepo,trans_type:trans_type,sub34:sub},function(result){
		      $('#getapprover').html(result);
		});
		
	}

	
	function getsubmit(){
		
		var row_affected 	=  $("#row_affected").val();
		var approval_role_1	=  $("#APPROVER_1").val();
		var approval_role_2	=  $("#APPROVER_2").val();
		var approval_role_3 =  $("#APPROVER_3").val();
		var approval_role_4 =  $("#APPROVER_4").val();
		var approval_role_5 =  $("#APPROVER_5").val();
		var approval_role_6 =  $("#APPROVER_6").val();
		var approval_role_7 =  $("#APPROVER_7").val();
		var approval_role_8 =  $("#APPROVER_8").val();

//alert(row_affected + ' ' + approval_role_1 + ' ' + approval_role_2 + ' ' + approval_role_3);

		if(row_affected==1){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
		}
		else if(row_affected==2){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
			else if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
		}
		if(row_affected==3){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
			else if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
			else if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
		}
		if(row_affected==4){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
			else if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
			else if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
			else if(approval_role_4==''){
				alert('Fourth Approval should select !!!');
				return false;
			}
		}
		
		if(row_affected==5){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
			else if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
			else if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
			else if(approval_role_4==''){
				alert('Fourth Approval should select !!!');
				return false;
			}
			else if(approval_role_5==''){
				alert('Fifth Approval should select !!!');
				return false;
			}
		}
		if(row_affected==6){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
			else if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
			else if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
			else if(approval_role_4==''){
				alert('Fourth Approval should select !!!');
				return false;
			}
			else if(approval_role_5==''){
				alert('Fifth Approval should select !!!');
				return false;
			}
			else if(approval_role_6==''){
				alert('Sixth Approval should select !!!');
				return false;
			}
		}
		if(row_affected==7){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
			else if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
			else if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
			else if(approval_role_4==''){
				alert('Fourth Approval should select !!!');
				return false;
			}
			else if(approval_role_5==''){
				alert('Fifth Approval should select !!!');
				return false;
			}
			else if(approval_role_6==''){
				alert('Sixth Approval should select !!!');
				return false;
			}
			else if(approval_role_7==''){
				alert('Seventh Approval should select !!!');
				return false;
			}
		}
		if(row_affected==8){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
			else if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
			else if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
			else if(approval_role_4==''){
				alert('Fourth Approval should select !!!');
				return false;
			}
			else if(approval_role_5==''){
				alert('Fifth Approval should select !!!');
				return false;
			}
			else if(approval_role_6==''){
				alert('Sixth Approval should select !!!');
				return false;
			}
			else if(approval_role_7==''){
				alert('Seventh Approval should select !!!');
				return false;
			}
			else if(approval_role_8==''){
				alert('Eighth Approval should select !!!');
				return false;
			}
		}
		

		
		return false;
		
	}
	
</script>


</body>
</html>
