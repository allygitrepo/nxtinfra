<?php

include("../header.php");
$modulePath = "payment/"; 
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Payment Entry
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.$modulePath; ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Payment Entry</li>
      </ol>
    </section>

<div class="col-md-12">
	 <div class="box">
		<div class="box box-info">
			<?php 
				
				$targetpage = "grid_utrno_edit.php?sub=list"; 
				$limit = 10; 
				$start = 0;	
				

				if ($_POST['comp_id'] or $_POST['status'] or $_POST['approval_status'] or $_POST['searchf']){
					$_SESSION['comp_id'] = $_POST['comp_id'];
					$_SESSION['statuss'] = $_POST['status'];
					$_SESSION['approval_status'] = $_POST['approval_status'];
					$_SESSION['searchf'] = $_POST['searchf'];
					$_SESSION['search_data'] = $_POST['search_data'];
					$_SESSION['start_date'] = $_POST['start_date'];
					$_SESSION['end_date'] = $_POST['end_date'];
					
				}
				
				if ( $_SESSION['comp_id'] or $_SESSION['approval_status'] or $_SESSION['statuss'] or $_SESSION['searchf'] ){
					$comp_id 		= $_SESSION['comp_id'];
					$status 		= $_SESSION['statuss'];
					$approval_status= $_SESSION['approval_status'];
					$searchf 		= $_SESSION['searchf'];
					$search_data 	= $_SESSION['search_data'];
					$start_date 	= $_SESSION['start_date'];
					$end_date 		= $_SESSION['end_date'];
					$_SESSION['reset']='';
				}
				
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['comp_id'] = '';
					$_SESSION['statuss'] = '';
					$_SESSION['approval_status'] = '';
					$_SESSION['searchf'] = '';
					$_SESSION['search_data'] = '';
					$_SESSION['start_date'] = '';
					$_SESSION['end_date'] = '';
					$comp_id = $_SESSION['comp_id'];
					$status = $_SESSION['statuss'];
					$searchf = $_SESSION['searchf'];
					$search_data = $_SESSION['search_data'];
					$start_date = $_SESSION['start_date'];
					$end_date = $_SESSION['end_date'];
					$approval_status = $_SESSION['approval_status'];
				}
				
			?>
					<form class="form-horizontal" action="grid_utrno_edit.php?sub=list" method="post">
                      
						<div class="form-group">
								
								<label class="col-lg-1 control-label">Company</label>
								<div class="col-md-3">
									<select class="form-control select2" name="comp_id" id="comp_id" >
										<option value=""> Select </option>
											<?php $sql = "select * from company order by comp_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" <?php echo ($comp_id == $r2['comp_id'])?'selected="selected"':'';?>  ><?php echo $r2['comp_name'];?></option>
											<?php } ?>
									</select>
										
								</div>
								
								<label for="reqDate" class="col-lg-1 control-label">Status</label>
								<div class="col-md-2">
                                       <select class="form-control select2" name="status" id="status" >
										<option value=""> Select </option>
										<option value="Draft" <?php echo ($status == 'Draft')?'selected="selected"':'';?> > Draft </option>
										<option value="Submited" <?php echo ($status == 'Submited')?'selected="selected"':'';?>> Submited </option>
										<option value="Verified" <?php echo ($status == 'Verified')?'selected="selected"':'';?>> Verified </option>
										<option value="Completed" <?php echo ($status == 'Completed')?'selected="selected"':'';?>> Completed </option>
										</select>
								</div>

								<label for="reqDate" class="col-lg-1 control-label">Decision</label>
								<div class="col-md-2">
                                       <select class="form-control select2" name="approval_status" id="approval_status" >
										<option value=""> Select </option>
										<option value="Approved" <?php echo ($approval_status == 'Approved')?'selected="selected"':'';?>> Approved </option>
										<option value="Pending" <?php echo ($approval_status == 'Pending')?'selected="selected"':'';?>> Pending </option>
										<option value="Verified" <?php echo ($approval_status == 'Verified')?'selected="selected"':'';?>> Verified </option>
										<option value="Rejected" <?php echo ($approval_status == 'Rejected')?'selected="selected"':'';?>> Rejected </option>
										</select>
								</div>
								
							<div class="col-xs-2">
                                		
							<input class="btn btn-primary" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
							<a href="grid_utrno_edit.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>
							
						</div>

						<div class="form-group">
							<label class="col-lg-1 control-label">Search.On</label>
							<div class="col-md-2">
								<select class="form-control select2" name="searchf" id="searchf" onchange="getsearchf(this.value)">
									<option value=""> Select </option>
									<option value="S" <?php echo ($searchf == 'S')?'selected="selected"':'';?>> Paid To Supplier</option>
									<option value="U" <?php echo ($searchf == 'U')?'selected="selected"':'';?>> Paid To Employee</option>
									<option value="D" <?php echo ($searchf == 'D')?'selected="selected"':'';?>> Date </option>
									<option value="O" <?php echo ($searchf == 'O')?'selected="selected"':'';?>> OWN </option>
									<option value="N" <?php echo ($searchf == 'N')?'selected="selected"':'';?>> Sr.No. </option>
									<option value="R" <?php echo ($searchf == 'R')?'selected="selected"':'';?>> UTR.No.</option>
									<option value="V" <?php echo ($searchf == 'V')?'selected="selected"':'';?>> Deactive </option>
									<option value="B" <?php echo ($searchf == 'B')?'selected="selected"':'';?>> Both </option>
									<option value="X" <?php echo ($searchf == 'X')?'selected="selected"':'';?>> Operating Exp </option>
									<option value="T" <?php echo ($searchf == 'T')?'selected="selected"':'';?>> Travel Exp </option>
									<option value="A" <?php echo ($searchf == 'A')?'selected="selected"':'';?>> Travel Advance </option>
									<option value="I" <?php echo ($searchf == 'I')?'selected="selected"':'';?>> Supplier Invocie </option>
									<option value="J" <?php echo ($searchf == 'J')?'selected="selected"':'';?>> Supplier Advance </option>
								</select>
											
							</div>

							<span id="getsearchf">
							<?php if($searchf=='N' || $searchf=='S' || $searchf=='U' || $searchf=='R'){ ?>	
								<div class="col-md-3">
								<?php if($searchf=='N' || $searchf=='R'){ 
										?>
									
									<input type="text" class="form-control" id="search_data" name="search_data" autocomplete="off" value="<?php echo $search_data?>" >
								<?php } ?>
								
							<?php if($searchf=='S'){ ?>
									<select class="form-control select2-123" name="search_data">
									<option value=""> Select </option>
									<option value=""> All</option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($search_data == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['party_name'];?></option>
										<?php } ?>
                                    </select>
							<?php }
							
							?>
							<?php if($searchf=='U'){ ?>
									<select class="form-control select2-123" name="search_data">
									<option value=""> Select </option>
									<option value=""> All</option>
										<?php $sql = "select * from sma_user order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($search_data == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['username'];?></option>
										<?php } ?>
                                    </select>
							<?php } ?>
							
								</div>
							<?php } ?>
							
							<?php if($searchf=='D'){ 
								$start_date = date('d-m-Y', strtotime($start_date));
								$end_date = date('d-m-Y', strtotime($end_date));
							?>	
								<label class="col-lg-1 control-label">Start.Date</label>
								<div class="col-md-2">
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="<?php echo $start_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>';
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
							
									<span class="pull-right"><?php for($x=0;$x<10;$x++){echo '&nbsp;';}?>
									</span>
										<span class="pull-right"><a href="payment_export.php?sub=pdf" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp; Report </a></span>
									<?php if($role=='Accountant'){ ?>
										<span class="pull-right"><a href="add.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Payment </a>&nbsp;&nbsp;</span>
									<?php } ?>	
									
						</div>

						<div class="form-group">
						</div>
				
				</form>

		</div>	
	
   
	<div class="box-body">
    <table id="prtable123" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>SrNo.</th>
			<th style="color:red" >Paid Date</th>
			<th>Paid via</th>
			<th>Paid To</th>
			<th style="color:red">Edit UTR.No.</th>
			<th>Dated.</th>
			<th>Supp.No.</td>
			<th>Inv Sr.No.</td>
			<th style="text-align:right;">Amount Paid</th>
		    <th>By</th>
			<th>Status</th>
			<th>Decision</th>
			
<!--			<th style="text-align:right;">Action</th>-->
    
		</tr>
	</thead>
<tbody>
<?php	
	$user   = $_SESSION['user'];
	$role	= $_SESSION['role'];
	$comid = $_SESSION['comid'];
	//$sql="SELECT * from payment_header order by id desc";

//echo $role.' ' . $user."<BR>";
	
	if ($role =='HOD - Account' || $role =='Project Manager'){
		$sql="SELECT * from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PY')) ";
		$query="SELECT count(*) as num from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PY')) ";
	}
	else if ($role =='Checker - Account' ){
		$sql="SELECT * from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PY')) ";
		$query="SELECT count(*) as num  from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PY')) ";
	}
	else if ($role =='Accountant'){
		$sql="SELECT * from payment_header where draft_by = '$user' and company_id in ($comid)  ";
		$query="SELECT count(*) as num from payment_header where draft_by = '$user' and company_id in ($comid)  ";
	}
	else if ( $role == 'Checker' || strpos( $role, 'Audit') !== false ){
		$sql="SELECT * from payment_header where  company_id in ($comid) ";//status = 'Completed' and
		$query="SELECT count(*) as num  from payment_header where  company_id in ($comid) ";//status = 'Completed' and
	//	echo $sql;
	}
	else if ( $role =='Maker'){
		$sql = "SELECT * from payment_header where status = 'Completed' and company_id in ($comid) ";
		//as per Rajesh <= and id in (select payment_hdr_id from payment_details where supp_id in ( select id from sma_supplier_invoice where draft_by = '$user' )) ";
		$query = "SELECT count(*) as num from payment_header where status = 'Completed' and company_id in ($comid) ";
		//as per Rajesh <= and id in (select payment_hdr_id from payment_details where supp_id in ( select id from sma_supplier_invoice where draft_by = '$user' )) ";
		//echo $sql;
	}
	else {
		$sql="SELECT * from payment_header where draft_by = '$user' ";
		$query="SELECT count(*) as num  from payment_header where draft_by = '$user' ";
	}
	
	if($user=='Admin' || $role =='CXO' ){
		$sql="SELECT * from payment_header where id > 0 ";
		$query="SELECT count(*) as num  from payment_header where id > 0 ";
	}
	
	//echo $_POST['comp_id']. ' <<>> '. $comp_id;
			
	if ($comp_id){
		$sql .= " and company_id = '$comp_id' ";
		$query .= " and company_id = '$comp_id' ";
	}
	if ($status){
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
						$sql .= " and paid_date >= '$start_date' and paid_date <= '$end_date' ";
						$query .= " and paid_date >= '$start_date' and paid_date <= '$end_date' ";
					}
					if($searchf=='N'){
						$sql .= " and id = '$search_data' ";
						$query .= " and  id = '$search_data'  ";
					}
					
					if($searchf=='R' ){
						if(empty($search_data)){
							$sql .= " and utr_no = '' ";
							$query .= " and  utr_no  = '' ";							
						}
						else {
							$sql .= " and utr_no like '%". $search_data ."%' ";
							$query .= " and  utr_no like '%". $search_data ."%' ";
						}
					}
					
					if($searchf=='O'){
						$sql .= " and draft_by = '$user' ";
						$query .= " and  draft_by = '$user'  ";
					}
					
					$sqlvn ='';
						
					if($searchf=='S'){
						if(!empty($search_data)){
							$sql .= " and paid_to = '$search_data' and (st_flag ='S' OR st_flag ='D' OR st_flag ='C' )";
							$query .= " and paid_to = '$search_data' and (st_flag ='S' OR st_flag ='D'  OR st_flag ='C' ) " ;
						}
						else {
							$sql .= "  and st_flag ='S' OR st_flag ='D' ";
							$query .= " and st_flag ='S' OR st_flag ='D' " ;
						}	
					}
					if($searchf=='U'){
						if(!empty($search_data)){
							$sql .= " and paid_to = '$search_data' and (st_flag ='A' OR st_flag ='T') ";
							$query .= " and paid_to = '$search_data' and (st_flag ='A' OR st_flag ='T')" ;
						}
						else {
							$sql .= "  and st_flag ='A' OR st_flag ='T' ";
							$query .= " and st_flag ='A' OR st_flag ='T' " ;
						}
						
					}
					
					if($searchf=='X'){
						$sql .= " and st_flag = 'C' ";
						$query .= " and st_flag = 'C' ";
					}
					else if($searchf=='T'){
						$sql .= " and (st_flag = 'T'  or st_flag = 'R') ";
						$query .= " and (st_flag = 'T'  or st_flag = 'R') ";
					}
					else if($searchf=='A'){
						$sql .= " and st_flag = 'A' ";
						$query .= " and st_flag = 'A' ";
					}
					else if($searchf=='I'){
						$sql .= " and st_flag = 'S' ";
						$query .= " and st_flag = 'S' ";
					}
					else if($searchf=='J'){
						$sql   .= " and st_flag = 'U' ";
						$query .= " and st_flag = 'U' ";
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
					
//echo $sql;
					$qresult = mysqli_query($con,$query);
					echo mysqli_error($con);
					$total_pages = mysqli_fetch_array($qresult);
					//$total_pages = mysqli_fetch_array(mysqli_query($con,$query));
					$total_pages = $total_pages[num];
					
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
	
	while($row = mysqli_fetch_array($result)){
		$cash_bank_name = $row['cash_bank_name'];
		$sql 	= "select * from account_mst where id = '$cash_bank_name' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$cash_bank_name = $r2['account_name'];
		
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
		
		if($st_flag=='S'){
			$st_flag ='SI';
		}
		else if($st_flag=='A'){
			$st_flag ='TA';
		}
		else if($st_flag=='T'){
			$st_flag ='TE';
		}
		else if($st_flag=='C'){
			$st_flag ='OE';
		}
		else if($st_flag=='D'){
			$st_flag ='SA';
		}
		
		$dated = date('d-m-Y', strtotime($row['dated']));
		if($dated =='01-01-1970'){
			$dated = '';
		}
		
		$rid = $row['id'];
		$sql = "SELECT supplier_invoice_no, supp_id FROM `payment_details` where payment_hdr_id = '$rid' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$supplier_invoice_no  = $r2['supplier_invoice_no'];
		$supp_id			  = $r2['supp_id'];
		
		if($st_flag =='OE' ){
			$sql = "SELECT * FROM `sma_expenses` where exp_type = 'C' and approval_ref_no = '$supplier_invoice_no' ";
			//echo $sql; exit();
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$supplier_invoice_no  = $r2['invoice_no'];
		}
		
			$styl  = '';
			$sty3  = '';
			$styl2 = '';
			$del   = $row['del'];
			if($del =='Y'){
				$styl = "style='bgcolor:powderblue;color:red;' ";
				$styl2 = "bgcolor:powderblue;color:red; ";
			}
			if(strlen($row['utr_no'])>=19){
				$sty3 = "font-size:11px; ";
			}	
			
		$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$row["id"];
				
		?>
	<tr>
		<td width="2%" style="text-align:right;<?php echo $styl2; ?>"><?php echo $row['id'];?></td>
		<td width="10%" style="text-align:right;color:red;" contenteditable="true" onBlur="saveToDatabase(this,'paid_date','<?php echo date('d-m-Y', strtotime($row['paid_date'])); ?>', '<?php echo $row['id'] ?>')" onClick="showEdit(this);" ><?= date('d-m-Y', strtotime($row['paid_date']));?></td>
		<td width="12%" <?php echo $styl; ?>><?php echo $cash_bank_name;?></td>
		<td width="12%"<?php echo $styl; ?>><?php echo $party_name;?></td>
		<td width="8%" style="text-align:right;color:red;<?= $sty3;?>" contenteditable="true" onBlur="saveToDatabase(this,'utr_no','<?php echo $row['utr_no']; ?>', '<?php echo $row['id'] ?>')" onClick="showEdit(this);"><?php echo $row['utr_no'];?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $dated;?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $supplier_invoice_no;?></td>
		<td width="6%"<?php echo $styl; ?>><?php echo $supp_id . '-' . $st_flag;?></td>
		<td width="10%" style="text-align:right;<?php echo $styl2; ?>"><?php echo moneyFormatIndia($row['total_amount_paid']);?></td>
		<td width="08%"<?php echo $styl; ?>><?php echo $row['changed_by'];?></td>
		<td width="08%"<?php echo $styl; ?>><?php echo $row['status'];?></td>
		<td width="08%"<?php echo $styl; ?>><?php echo $row['approval_status'];?></td>

    </tr>
	
	<?php }
	
	
function moneyFormatIndia($num){
        $nums = explode(".",$num);
        if(count($nums)>2){
            return "0";
        }else{
        if(count($nums)==1){
            $nums[1]="00";
        }
        $num = $nums[0];
        $explrestunits = "" ;
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); 
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; 
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++){

                if($i==0)
                {
                    $explrestunits .= (int)$expunit[$i].","; 
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }

		if($thecash==0){
			$nums = $nums[1];
			if($nums==0){
				$nums[1] = '';
			}
			$thecash = '';
		}
		else{
			$thecash = $thecash.".".$nums[1];
		}
        
		return $thecash;
    }
}
?>
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

<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="jui/js/jquery-ui-1.9.2.min.js"></script>
<script>
	function showEdit(editableObj) {
			$(editableObj).css("background","#FFF");
			
			//alert("Marks should not be more than 100...Wrong Entered marks is-> ");
		}
		
		function saveToDatabase(editableObj,column,utrno,pay_id){
		    
			var qty = editableObj.innerHTML;
			
		//	if (rate>100){
		//		alert(editableObj.innerHTML +   ' ' + pay_id);
		//		alert("Marks should not be more than 100...Wrong Entered marks is-> "+column);
		//		return false;
		//	}
		//	if (rate<0){
		//		alert("Marks should not be less than zero...Wrong Entered marks is-> "+rate);
		//		return false;
		//	}
		
		//   if (rate<=100){
			$(editableObj).css("background","#FFF  no-repeat right");
	
			$.ajax({
				url: "saveutrno.php",
				type: "POST",
				data:'column='+column+'&editval='+editableObj.innerHTML+'&utrno='+utrno+'&pay_id='+pay_id,
				success: function(data){
				    $(editableObj).css("background","#FDFDFD");
				}
				
		   });
		//  }
	   }
	   
	   
</script>
	
<?php include "../footer.php"; ?>

<!-- jQuery 2.2.3 -->
<script src="<?php echo $baseurl . "plugins/jQuery/jquery-2.2.3.min.js"?>"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo $baseurl . "bootstrap/js/bootstrap.min.js"?>"></script>
<!-- DataTables -->

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"?>"></script>

<!-- SlimScroll -->
<script src=<?php echo $baseurl . "plugins/slimScroll/jquery.slimscroll.min.js"?>"></script>
<!-- FastClick -->
<script src="<?php echo $baseurl . "plugins/fastclick/fastclick.js"?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo $baseurl . "dist/js/app.min.js"?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo $baseurl . "dist/js/demo.js"?>"></script>
<!-- page script -->

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>


<script>
    $(function () {
        $("#prtable").DataTable();
    });
</script>

<script>
  $(function () {
  //  $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
    $('#example1').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": true
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
  
</script>

