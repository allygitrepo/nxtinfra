<?php
$pgname = "advance/index.php";
include("../viewonly.php");
$sub_menu_hdr = $main_menu;

include("../header.php");
$modulePath = "advance/";

$help_code = $modulePath . 'index.php';
include "../help_code.php";

$pgname = $help_code;
include("../viewonly.php");

?>
<!-- DataTables -->
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css" ?>">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?= $sub_menu; ?> <small>List</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo $baseurl . 'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
			<li class="active"><?= $sub_menu; ?></li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">

						<?php

						$targetpage = "index.php?sub=list";
						$limit = 10;
						$start = 0;
						$comid = $_SESSION['comid'];

						if ($_POST['party_id'] or $_POST['comp_id'] or $_POST['department_id'] or $_POST['status'] or $_POST['approval_status'] or $_POST['searchf'] or $_POST['search_own'] or $_POST['start_date']) {
							$_SESSION['comp_id'] = $_POST['comp_id'];
							$_SESSION['party_id'] = $_POST['party_id'];
							
							$_SESSION['department_id'] = $_POST['department_id'];
							$_SESSION['status'] = $_POST['status'];
							$_SESSION['searchf'] = $_POST['searchf'];
							$_SESSION['search_own'] = $_POST['search_own'];
							$_SESSION['search_data'] = $_POST['search_data'];
							$_SESSION['start_date'] = $_POST['start_date'];
							$_SESSION['end_date'] = $_POST['end_date'];
							$_SESSION['approval_status'] = $_POST['approval_status'];
							$_SESSION['reset'] = '';
						}

						if ($_SESSION['party_id'] or $_SESSION['comp_id']  or $_SESSION['department_id'] or $_SESSION['approval_status'] or $_SESSION['status'] or $_SESSION['searchf'] or $_SESSION['search_own'] or $_POST['start_date']) {
							$comp_id = $_SESSION['comp_id'];
							$party_id = $_SESSION['party_id'];
							
							$department_id = $_SESSION['department_id'];
							$status = $_SESSION['status'];
							$searchf = $_SESSION['searchf'];
							$search_own = $_SESSION['search_own'];
							$search_data = $_SESSION['search_data'];
							$start_date = $_SESSION['start_date'];
							$end_date = $_SESSION['end_date'];
							$approval_status = $_SESSION['approval_status'];
						}

						if (!empty($_GET['reset']) || !empty($_SESSION['reset'])) {
							$_SESSION['comp_id'] = '';
							$_SESSION['party_id'] = '';
							
							$_SESSION['department_id'] = '';
							$_SESSION['status'] = '';
							$_SESSION['searchf'] = '';
							$_SESSION['search_own'] = '';
							$_SESSION['search_data'] = '';
							$_SESSION['start_date'] = '';
							$_SESSION['end_date'] = '';
							$_SESSION['approval_status'] = '';
							$comp_id = $_SESSION['comp_id'];
							$party_id = $_SESSION['party_id'];
							
							$department_id = $_SESSION['department_id'];
							$status = $_SESSION['status'];
							$searchf = $_SESSION['searchf'];
							
							$search_own = $_SESSION['search_own'];
							$search_data = $_SESSION['search_data'];
							$start_date = $_SESSION['start_date'];
							$end_date = $_SESSION['end_date'];
							$approval_status = $_SESSION['approval_status'];
							$_SESSION['reset'] = '';
							$_SESSION['Createdby'] = '';
							$_SESSION['PendingPO'] = '';
						}

						?>
						<form class="form-horizontal" action="index.php?sub=list" method="post">

							<div class="form-group">

								<div class="col-md-3">
									<label class=" control-label">Company</label>
									<select class="form-control select2" name="comp_id" id="comp_id" >
										<option value=""> Select </option>
										<?php $sql = "select * from company where 1 order by comp_name ";
										$q2 = mysqli_query($con, $sql);
										while ($r2 = mysqli_fetch_array($q2)) { ?>
											<option value="<?php echo $r2['comp_id']; ?>" <?php echo ($comp_id == $r2['comp_id']) ? 'selected="selected"' : ''; ?>>
												<?php echo $r2['comp_name']; ?></option>
										<?php } ?>
									</select>
								</div>

								<div class="col-md-3">
									<label class=" control-label">Department</label>
								
									<select class="form-control select2" name="department_id" id="department_id">
										<option value=""> Select </option>
										<?php $sql = "select * from sma_department order by name ";
										$q2 = mysqli_query($con, $sql);
										while ($r2 = mysqli_fetch_array($q2)) { ?>
											<option value="<?php echo $r2['id']; ?>" <?php echo ($department_id == $r2['id']) ? 'selected="selected"' : ''; ?>>
												<?php echo $r2['name']; ?></option>
										<?php } ?>
									</select>
								</div>
							
								<div class="col-md-3">
									<label class=" control-label">Supplier</label>
								
									<select class="form-control select2" name="party_id" id="party_id" >
										<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst where 1 order by party_name ";
										$q2 = mysqli_query($con, $sql);
										while ($r2 = mysqli_fetch_array($q2)) { ?>
											<option value="<?php echo $r2['id']; ?>" <?php echo ($party_id == $r2['id']) ? 'selected="selected"' : ''; ?>>
												<?php echo $r2['party_name']; ?></option>
										<?php } ?>
									</select>
								</div>
								
							</div>

							<div class="form-group">
								<label for="reqDate" class="col-lg-1 control-label">Status</label>
								<div class="col-md-2">
									<select class="form-control select2" name="status" id="status">
										<option value=""> Select </option>
										<option value="Draft" <?php echo ($status == 'Draft') ? 'selected="selected"' : ''; ?>>
											Draft </option>
										<option value="Submitted" <?php echo ($status == 'Submitted') ? 'selected="selected"' : ''; ?>> Submitted </option>
										<!--<option value="Verified" <?php echo ($status == 'Verified') ? 'selected="selected"' : ''; ?>> Verified </option>-->
										<option value="Completed" <?php echo ($status == 'Completed') ? 'selected="selected"' : ''; ?>> Completed </option>
										<option value="Uploaded" <?php echo ($status == 'Uploaded') ? 'selected="selected"' : ''; ?>> Uploaded </option>
										<option value="PendingPO" <?php echo ($status == 'PendingPO') ? 'selected="selected"' : ''; ?>> Pending For PO </option>

									</select>
								</div>

								<label for="reqDate" class="col-lg-1 control-label">Decision</label>
								<div class="col-md-2">
									<select class="form-control select2" name="approval_status" id="approval_status">
										<option value=""> Select </option>
										<option value="Approved" <?php echo ($approval_status == 'Approved') ? 'selected="selected"' : ''; ?>> Approved
										</option>
										<option value="Submitted" <?php echo ($approval_status == 'Submitted') ? 'selected="selected"' : ''; ?>> Pending
										</option>
										<!--<option value="Verified" <?php echo ($approval_status == 'Verified') ? 'selected="selected"' : ''; ?>> Verified-->
										<!--</option>-->
										<option value="Rejected" <?php echo ($approval_status == 'Rejected') ? 'selected="selected"' : ''; ?>> Rejected
										</option> 
									</select>
								</div>

								<div class="col-md-6 text-right">
									<input class="btn btn-primary" type="submit" value="Search" name="Save">&nbsp;
									<a href="index.php?sub=list&reset=1" name="btnCancel"
										class="btn btn-primary btn-inverse"><i
											class="splashy-refresh_backwards"></i>&nbsp;Reset</a>&nbsp;

								<!--	<a href="#modalExport" class="btn btn-primary" data-toggle="modal"
										data-mode="add" data-target="#modalExport">Export</a>&nbsp;-->

									<?php if ( empty($viewonly) ){ // || empty($addonly) ?>	
										<a href="<?php echo $baseurl . $modulePath . "add.php" ?>"
											class="btn btn-primary">Create</a>
									<?php } ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-1 control-label">Search.On</label>
								<div class="col-md-2">
									<select class="form-control select2" name="searchf" id="searchf"
										onchange="getsearchf(this.value)">
										<option value=""> Select </option>
										<option value="D" <?php echo ($searchf == 'D') ? 'selected="selected"' : ''; ?>>
											Date </option>
										<option value="N" <?php echo ($searchf == 'N') ? 'selected="selected"' : ''; ?>>
											Sr.No. </option>
										<option value="M" <?php echo ($searchf == 'M') ? 'selected="selected"' : ''; ?>>
											PO.Number. </option>
										<option value="P" <?php echo ($searchf == 'P') ? 'selected="selected"' : ''; ?>>
											PO.Srno. </option>
										<option value="T" <?php echo ($searchf == 'T') ? 'selected="selected"' : ''; ?>>
											Deleted </option>
										<option value="C" <?php echo ($searchf == 'C') ? 'selected="selected"' : ''; ?>>
											Creator(Usrid) </option>
										<option value="X" <?php echo ($searchf == 'X') ? 'selected="selected"' : ''; ?>>
											Text </option>


									</select>
								</div>

								<div class="col-xs-1">
									<input class="btn btn-success" type="submit" value="Self Created"
										name="Createdby">
								</div>
							<!--</div>-->

							<!--<div class="form-group">-->
								<span id="getsearchf">
									<?php if ($searchf == 'N' || $searchf == 'P' || $searchf == 'M' || $searchf == 'C' || $searchf == 'X') { ?>
										<div class="col-md-3">
											<?php if ($searchf == 'N' || $searchf == 'P' || $searchf == 'M' || $searchf == 'C' || $searchf == 'X') { ?>
												<input type="text" class="form-control" id="search_data" name="search_data"
													autocomplete="off" value="<?php echo $search_data ?>">
											<?php } ?>

										</div>
									<?php } ?>

									<?php

									if ($searchf == 'D') {
										$start_date = date('d-m-Y', strtotime($start_date));
										$end_date = date('d-m-Y', strtotime($end_date));
										if ($start_date == '01-01-1970') {
											$start_date = date('d-m-Y');
										}
										if ($end_date == '01-01-1970') {
											$end_date = date('d-m-Y');
										}

										?>
										<label class="col-lg-1 control-label">Start.Date</label>
										<div class="col-md-2">
											<div class="input-group date" data-provide="datepicker"
												data-date-format="dd-mm-yyyy">
												<input type="text" class="form-control" id="start_date"
													name="start_date" autocomplete="off"
													value="<?php echo $start_date; ?>">
												<div class="input-group-addon">
													<i class="fa fa-calendar-alt"></i>
												</div>
											</div>
										</div>
										<label class="col-lg-1 control-label">End.Date</label>
										<div class="col-md-2">
											<div class="input-group date" data-provide="datepicker"
												data-date-format="dd-mm-yyyy">
												<input type="text" class="form-control" id="end_date" name="end_date"
													autocomplete="off" value="<?php echo $end_date; ?>">
												<div class="input-group-addon">
													<i class="fa fa-calendar-alt"></i>
												</div>
											</div>
										</div>
									<?php } ?>

								</span>


							</div>

						</form>


					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<?php
						$sql = "SELECT * FROM sma_advance where 1 and company_id in ($comid) ";

						$sql = "SELECT * from sma_advance where  1 AND company_id in ( $comid ) ";
						$query = "SELECT count(*) as num  from sma_advance where del !='Y' AND company_id in ( $comid ) ";

						$sql .= " and (status = 'Draft' or status = 'Submitted' or status = 'Completed' or approval_status = 'Rejected' or current_approver = '$usrid' or draft_by = '$user' ) ";

						if ($user == 'Admin' || $primaryrole == 'COO' || $primaryrole == 'Director') {
							$sql = "SELECT * from sma_advance where 1 AND id > 0 ";
							$query = "SELECT count(*) as num  from sma_advance where del !='Y' AND id > 0 ";
						}

						if ($viewonly == 'Y') {
							$sql = "SELECT * from sma_advance where 1 AND id > 0 and company_id in ($comid )";
							$query = "SELECT count(*) as num  from sma_advance where del !='Y' AND id > 0 and company_id in ( $comid ) ";
						}

						if ($_POST['Createdby'] || $_SESSION['Createdby']) {
							if ($_POST['Createdby']) {
								$_SESSION['Createdby'] = $_POST['Createdby'];
							}
							$sql = "SELECT * from sma_advance where company_id in ( $comid ) and draft_by = '$user' ";
							$query = "SELECT count(*) as num  from sma_advance where 1 AND company_id in ( $comid ) and draft_by = '$user' ";

						}

						if ($status == 'PendingPO' || $_SESSION['PendingPO'] == 'PendingPO') {
							if ($status == 'PendingPO') {
								$_SESSION['PendingPO'] = $status;
							}
							$status = '';
							$_SESSION['status'] = '';
							$sql .= " and id not in (SELECT approval_memo_ref FROM `sma_purchase_order` where project in ( $comid ) ) ";
							$query .= " and id not in (SELECT approval_memo_ref FROM `sma_purchase_order` where project in ( $comid ) ) ";
						}

						if ($comp_id) {
							$sql .= " and company_id = '$comp_id' ";
							$query .= " and company_id = '$comp_id' ";
						}
						if ($party_id) {
							$sql .= " and supplier_id = '$party_id' ";
							$query .= " and supplier_id = '$party_id' ";
						}
						if ($status) {
							if ($status == 'Uploaded') {
								$sql .= " and upd_flag= 'O' ";
								$query .= " and upd_flag= 'O' ";
							} else {
								$sql .= " and status = '$status' ";
								$query .= " and status = '$status' ";
							}
						}
						if ($approval_status) {
							$sql .= " and approval_status = '$approval_status' ";
							$query .= " and approval_status = '$approval_status' ";
						}
						
						if ($department_id) {
							$sql .= " and department = '$department_id' ";
							$query .= " and department = '$department_id' ";
						}

						if ($searchf == 'D') {
							//echo		$start_date = date('Y-m-d', strtotime($_SESSION['start_date']));
							//		$end_date = date('Y-m-d', strtotime($_SESSION['end_date']));
							$start_date = date('Y-m-d', strtotime($start_date));
							$end_date = date('Y-m-d', strtotime($end_date));
							$sql .= " and date >= '$start_date' and date <= '$end_date' ";
							$query .= " and date >= '$start_date' and date <= '$end_date' ";
						}
						if ($searchf == 'N') {
							$sql .= " and id = '$search_data' ";
							$query .= " and  id = '$search_data' ";
						}


						if ($searchf == 'C') {
							$sql .= " and draft_by = '$search_data' ";
							$query .= " and draft_by = '$search_data'  ";
						}

						if ($searchf == 'X') {
							$sql .= " and subject like '%$search_data%' ";
							$query .= " and subject like '%$search_data%'  ";
						}


						if ($searchf == 'T') {
							$sql .= " and del = 'Y' ";
							$query .= " and del = 'Y' ";
						} else {
							$sql .= " and del != 'Y' ";
							$query .= " and del != 'Y' ";
						}

						// Start pagination					
						$qresult = mysqli_query($con, $query);
						echo mysqli_error($con);
						$total_pages = mysqli_fetch_array($qresult);
						//$total_pages = mysqli_fetch_array(mysqli_query($con,$query));
						$total_pages = $total_pages['num'];

						$stages = 3;
						//$page = mysqli_real_escape_string($_GET['page']);
						
						$page = ($_GET['page']);
						if ($page) {
							$start = ($page - 1) * $limit;
						} else {
							$start = 0;
						}

						$sql .= ' ORDER BY id DESC, dated  ';

					//	$sql .= " LIMIT $start, $limit ";

						//echo $sql;
						
						// Initial page num setup
						if ($page == 0) {
							$page = 1;
						}
						$prev = $page - 1;
						$next = $page + 1;
						$lastpage = ceil($total_pages / $limit);
						$LastPagem1 = $lastpage - 1;

						$paginate = '';
						//echo $lastpage;
						//echo $paginate;
						if ($lastpage > 1) {
							$paginate .= '<div style="float:right"><ul class="pagination pagination-lg">';
							// Previous
							if ($page > 1) {
								$paginate .= "<li><a href='$targetpage&page=$prev'>previous</a></li>";
							} else {
								$paginate .= "<li class='disabled'><a>previous</a></li>";
							}

							// Pages	
							if ($lastpage < 7 + ($stages * 2))	// Not enough pages to breaking it up
							{
								for ($counter = 1; $counter <= $lastpage; $counter++) {
									if ($counter == $page) {
										$paginate .= "<li class='active'><a>$counter</a></li></span>";
									} else {
										$paginate .= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";
									}
								}
							} elseif ($lastpage > 5 + ($stages * 2))	// Enough pages to hide a few?
							{
								// Beginning only hide later pages
								if ($page < 1 + ($stages * 2)) {
									for ($counter = 1; $counter < 4 + ($stages * 2); $counter++) {
										if ($counter == $page) {
											$paginate .= "<li class='active'><a>$counter</a></li>";
										} else {
											$paginate .= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";
										}
									}
									$paginate .= "<li><a>...</a></li>";
									$paginate .= "<li><a href='$targetpage&page=$LastPagem1'>$LastPagem1</a></li>";
									$paginate .= "<li><a href='$targetpage&page=$lastpage'>$lastpage</a></li>";
								}
								// Middle hide some front and some back
								elseif ($lastpage - ($stages * 2) > $page && $page > ($stages * 2)) {
									$paginate .= "<li><a href='$targetpage&page=1'>1</a></li>";
									$paginate .= "<li><a href='$targetpage&page=2'>2</a></li>";
									$paginate .= "<li><a>...</a></li>";
									for ($counter = $page - $stages; $counter <= $page + $stages; $counter++) {
										if ($counter == $page) {
											$paginate .= "<li class='active'><a>$counter</a></li>";
										} else {
											$paginate .= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";
										}
									}
									$paginate .= "<li><a>...</a></li>";
									$paginate .= "<li><a href='$targetpage&page=$LastPagem1'>$LastPagem1</a></li>";
									$paginate .= "<li><a href='$targetpage&page=$lastpage'>$lastpage</a></li>";
								}
								// End only hide early pages
								else {
									$paginate .= "<li><a href='$targetpage&page=1'>1</a></li>";
									$paginate .= "<li><a href='$targetpage&page=2'>2</a></li>";
									$paginate .= "<li><a>...</a></li>";
									for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++) {
										if ($counter == $page) {
											$paginate .= "<li class='active'><a>$counter</a></li>";
										} else {
											$paginate .= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";
										}
									}
								}
							}

							// Next
							if ($page < $counter - 1) {
								$paginate .= "<li><a href='$targetpage&page=$next'>next</a></li>";
							} else {
								$paginate .= "<li class='disabled'><a>next</a></li>";
							}

							$paginate .= "</ul></div>";
						}
						//end page
//echo $sql ."<BR>";
						
						?>
						<table id="prtable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th></th>
									<th>SR.No.</th>
									<th>Supplier Name</th>
									<th>Dated</th>
									<th>Company</th>
									<th>PO number</th>
									<th>PO Amount</th>
									<th>Advance Amount</th>
									<th>By</th>

									<th>Status</th>
									
									<!--<th>Pending With</th><th style="text-align:right;font-size:12px;">Action</th>-->
								</tr>
							</thead>
							<tbody>
								<?php
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								while ($row = mysqli_fetch_array($result)) {
									$rid = $row['id'];
									$baseurl1 = $baseurl . $modulePath . 'edit.php?sub=edit&id=' . $row["id"];
									
									$po_ref_no 			= $row['po_ref_no'];
									$advance_amount 	= $row['advance_amount'];
									$total_po_amount 	= $row['total_po_amount'];

									$sql = " SELECT * FROM `sma_purchase_order` where id  = '$po_ref_no' and del !='Y' ";
									$q2 = mysqli_query($con, $sql);
									$r2 = mysqli_fetch_array($q2);
									$po_number = $r2['po_number'];
									
									$company_id = $row['company_id'];
									$sql = "select * from company where comp_id = '$company_id' ";
									$q2 = mysqli_query($con, $sql);
									$r2 = mysqli_fetch_array($q2);
									$comp_code = $r2['comp_code'];

									$supplier_id 	= $row['supplier_id'];
									$sql 	= "select * from sma_party_mst where id = '$supplier_id' ";
									$q2 	= mysqli_query($con, $sql);
									$r2 	= mysqli_fetch_array($q2);
									$party_name = $r2['party_name'];

									$department_id = $row['department_id'];
									$sql = "select * from sma_department where id = '$department_id' ";
									$q2 = mysqli_query($con, $sql);
									$r2 = mysqli_fetch_array($q2);
									$department_name = $r2['name'];

									$dated = $row['dated'];
									$changed_by = $row['changed_by'];
									$draft_by = $row['draft_by'];
									$sql = "select * from sma_user where userid = '$draft_by' ";
									$q2 = mysqli_query($con, $sql);
									$r2 = mysqli_fetch_array($q2);
									$changed_by = $r2['username'];

									$approver_1 = $row['approver_1'];
									$approver_2 = $row['approver_2'];
									$approver_3 = $row['approver_3'];
									$approver_4 = $row['approver_4'];
									$approver_5 = $row['approver_5'];
									$approver_6 = $row['approver_6'];
									$approver_7 = $row['approver_7'];
									$approver_8 = $row['approver_8'];
									$approver_9 = $row['approver_9'];

									$approver_1_status = $row['approver_1_status'];
									$approver_2_status = $row['approver_2_status'];
									$approver_3_status = $row['approver_3_status'];
									$approver_4_status = $row['approver_4_status'];
									$approver_5_status = $row['approver_5_status'];
									$approver_6_status = $row['approver_6_status'];
									$approver_7_status = $row['approver_7_status'];
									$approver_8_status = $row['approver_8_status'];
									$approver_9_status = $row['approver_9_status'];
									$pending_by = '';
									if ($approver_1_status == 'Submitted') {
										$pending_by = $approver_1;
									}
									if ($approver_2_status == 'Submitted') {
										$pending_by = $approver_2;
									}
									if ($approver_3_status == 'Submitted') {
										$pending_by = $approver_3;
									}
									if ($approver_4_status == 'Submitted') {
										$pending_by = $approver_4;
									}
									if ($approver_5_status == 'Submitted') {
										$pending_by = $approver_5;
									}
									if ($approver_6_status == 'Submitted') {
										$pending_by = $approver_6;
									}
									if ($approver_7_status == 'Submitted') {
										$pending_by = $approver_7;
									}
									if ($approver_8_status == 'Submitted') {
										$pending_by = $approver_8;
									}
									if ($approver_9_status == 'Submitted') {
										$pending_by = $approver_9;
									}

									$upd_flag_v = '';
									$upd_flag = $row['upd_flag'];
									if ($upd_flag == 'O') {
										$upd_flag_v = '(Uploaded)';
									}

									if (!empty($pending_by)) {
										$sql = "select * from sma_user where id = '$pending_by' ";
										$q2 = mysqli_query($con, $sql);
										$r2 = mysqli_fetch_array($q2);
										$pending_by = ' To <br>' . $r2['username'];
									}

									$styl = "";
									$del = $row['del'];
									if ($del == 'Y') {
										$styl = "style='bgcolor:powderblue;color:red;' ";
										//	$styl2 = "bgcolor:powderblue;color:red; ";
								
									}

									?>
								
									<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=" . $row['id'] ?>"
										title="Edit">
										<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)"
											onmouseout="RestoreBackgroundColor(this)"
											onclick="location.href='<?php echo $baseurl1; ?>'">

											<td width="0%"><input type="hidden" value="<?= ++$ii; ?>"> </td>
											<td width="5%" <?= $styl; ?> style="text-align:right;"> <?php echo $row['id']; ?></td>
											<td width="20%" <?= $styl; ?>><?php echo $party_name; ?></td>
											<td width="10%" <?= $styl; ?>>
												<?php echo DateTime::createFromFormat('Y-m-d', $dated)->format('d-m-Y'); ?>
											</td>
											
											<td width="10%" <?= $styl; ?>><?php echo $comp_code; ?></td>

											<td width="15%" <?= $styl; ?> style="text-align:right;"><?php echo $po_number; ?> </td>
											
											<td width="10%" <?= $styl; ?> style="text-align:right;"><?php echo $total_po_amount; ?></td>
											<td width="10%" <?= $styl; ?> style="text-align:right;"><?php echo $advance_amount; ?></td>
											<td width="10%" <?= $styl; ?>><?php echo $changed_by; ?></td>
											
											<td width="10%" <?= $styl; ?>>
												<?php echo $row['status'] . ' ' . $pending_by . "<BR>" . $upd_flag_v; ?>
											</td>
											

										</tr>
									</a>
									<?php
								}
								?>
							</tbody>
							<tfoot>
							</tfoot>
						</table>

						<?php
						$end = $start + 10;
						$begin = $start + 1;

						if ($end > $total_pages) {
							$end = $total_pages;
						}
						//echo 'Showing ' . $begin . ' to ' . $end . ' of ' . $total_pages . ' entries ';
						//echo $paginate;
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

<!-- Modal Add Item-->
<div class="modal fade" id="modalExport" role="dialog" aria-labelledby="modalExportLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="modalExportLabel">Export Purchase Requisition data</h4>
			</div>
			<div class="modal-body123">
				<section class="content">
					<div class="row123">
						<form class="form-horizontal" action="pr_export_func.php?sub=pdf" target="_blank" method="POST">
							<input type="hidden" id="mode" value='Export'>
							<input type="hidden" id="tempId">
							<!--							<input type="hidden" id="purchaseId" value="<?php echo $_GET['id']; ?>">-->

							<div class="form-group">

								<div class="col-sm-4">
									<label class="control-label">From Date</label>
									<div class="input-group date" data-provide="datepicker"
										data-date-format="dd-mm-yyyy" required="required">
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
										<input type="text" class="form-control" id="fromDate" name="from_date"
											required="required">
									</div>
								</div>

								<div class="col-sm-4">
									<label class="control-label">To Date</label>
									<div class="input-group date" data-provide="datepicker"
										data-date-format="dd-mm-yyyy">
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
										<input type="text" class="form-control" id="toDate" name="to_date">
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-12">
									<label for="itemCategory" class="control-label"> Company</label>
									<select class="form-control" name="company_id" id="companyId" onchange="getproject_filter(this.value)">
										<option value=""> Select </option>
										<option value=""> All </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 = mysqli_query($con, $sql);
										while ($r2 = mysqli_fetch_array($q2)) { ?>
											<option value="<?php echo $r2['comp_id']; ?>"> <?php echo $r2['comp_name']; ?>
											</option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">

								<div class="col-sm-6">
									<label class="control-label">Department</label>
									<select class="form-control" name="department" id="departMent" <?php echo $readonly; ?>>
										<option value=""> Select </option>
										<option value=""> All </option>
										<?php $sql = "select * from sma_department order by name ";
										$q2 = mysqli_query($con, $sql);
										while ($r2 = mysqli_fetch_array($q2)) { ?>
											<option value="<?php echo $r2['id']; ?>"><?php echo $r2['name']; ?></option>
										<?php } ?>
									</select>
								</div>
							
								<div class="col-sm-6">
									<label class="control-label">Status</label>
									<select class="form-control" name="status" id="status" >
										<option value=""> Select </option>
										<option value="" selected> All </option>
										<option value="Approved" > Approved</option>
										<option value="Submitted" > Submitted</option>
										<option value="Rejected" > Rejected</option>
									</select>
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-6">
									<label class="control-label">Export with Product Details</label>
									<select class="form-control" name="export_product_details" id="exportProductDetails">
										<option value="Yes" selected>Yes</option>
										<option value="No">No</option>
									</select>
								</div>
							</div>

							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<input type="submit" class="btn btn-primary" name="submit" id="exportItem12"
									onclick="exportItem123()" value="Submit">
							</div>
						</form>
					</div>
				</section>
			</div>

		</div>
	</div>
</div>
<!-- Modal Add Item-->

<!-- /.content-wrapper -->

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
	<!-- Create the tabs -->
	<ul class="nav nav-tabs nav-justified control-sidebar-tabs">
		<li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
		<li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
	</ul>
	<!-- Tab panes -->
	<div class="tab-content">
		<!-- Home tab content -->
		<div class="tab-pane" id="control-sidebar-home-tab">
			<h3 class="control-sidebar-heading">Recent Activity</h3>
			<ul class="control-sidebar-menu">
				<li>
					<a href="javascript:void(0)">
						<i class="menu-icon fa fa-birthday-cake bg-red"></i>

						<div class="menu-info">
							<h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

							<p>Will be 23 on April 24th</p>
						</div>
					</a>
				</li>
				<li>
					<a href="javascript:void(0)">
						<i class="menu-icon fa fa-user bg-yellow"></i>

						<div class="menu-info">
							<h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

							<p>New phone +1(800)555-1234</p>
						</div>
					</a>
				</li>
				<li>
					<a href="javascript:void(0)">
						<i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

						<div class="menu-info">
							<h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

							<p>nora@example.com</p>
						</div>
					</a>
				</li>
				<li>
					<a href="javascript:void(0)">
						<i class="menu-icon fa fa-file-code-o bg-green"></i>

						<div class="menu-info">
							<h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

							<p>Execution time 5 seconds</p>
						</div>
					</a>
				</li>
			</ul>
			<!-- /.control-sidebar-menu -->

			<h3 class="control-sidebar-heading">Tasks Progress</h3>
			<ul class="control-sidebar-menu">
				<li>
					<a href="javascript:void(0)">
						<h4 class="control-sidebar-subheading">
							Custom Template Design
							<span class="label label-danger pull-right">70%</span>
						</h4>

						<div class="progress progress-xxs">
							<div class="progress-bar progress-bar-danger" style="width: 70%"></div>
						</div>
					</a>
				</li>
				<li>
					<a href="javascript:void(0)">
						<h4 class="control-sidebar-subheading">
							Update Resume
							<span class="label label-success pull-right">95%</span>
						</h4>

						<div class="progress progress-xxs">
							<div class="progress-bar progress-bar-success" style="width: 95%"></div>
						</div>
					</a>
				</li>
				<li>
					<a href="javascript:void(0)">
						<h4 class="control-sidebar-subheading">
							Laravel Integration
							<span class="label label-warning pull-right">50%</span>
						</h4>

						<div class="progress progress-xxs">
							<div class="progress-bar progress-bar-warning" style="width: 50%"></div>
						</div>
					</a>
				</li>
				<li>
					<a href="javascript:void(0)">
						<h4 class="control-sidebar-subheading">
							Back End Framework
							<span class="label label-primary pull-right">68%</span>
						</h4>

						<div class="progress progress-xxs">
							<div class="progress-bar progress-bar-primary" style="width: 68%"></div>
						</div>
					</a>
				</li>
			</ul>
			<!-- /.control-sidebar-menu -->
		</div>
		<!-- /.tab-pane -->
		<!-- Stats tab content -->
		<div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
		<!-- /.tab-pane -->
		<!-- Settings tab content -->
		<div class="tab-pane" id="control-sidebar-settings-tab">
			<form method="post">
				<h3 class="control-sidebar-heading">General Settings</h3>

				<div class="form-group">
					<label class="control-sidebar-subheading">
						Report panel usage
						<input type="checkbox" class="pull-right" checked>
					</label>

					<p>
						Some information about this general settings option
					</p>
				</div>
				<!-- /.form-group -->

				<div class="form-group">
					<label class="control-sidebar-subheading">
						Allow mail redirect
						<input type="checkbox" class="pull-right" checked>
					</label>

					<p>
						Other sets of options are available
					</p>
				</div>
				<!-- /.form-group -->

				<div class="form-group">
					<label class="control-sidebar-subheading">
						Expose author name in posts
						<input type="checkbox" class="pull-right" checked>
					</label>

					<p>
						Allow the user to show his name in blog posts
					</p>
				</div>
				<!-- /.form-group -->

				<h3 class="control-sidebar-heading">Chat Settings</h3>

				<div class="form-group">
					<label class="control-sidebar-subheading">
						Show me as online
						<input type="checkbox" class="pull-right" checked>
					</label>
				</div>
				<!-- /.form-group -->

				<div class="form-group">
					<label class="control-sidebar-subheading">
						Turn off notifications
						<input type="checkbox" class="pull-right">
					</label>
				</div>
				<!-- /.form-group -->

				<div class="form-group">
					<label class="control-sidebar-subheading">
						Delete chat history
						<a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
					</label>
				</div>
				<!-- /.form-group -->
			</form>
		</div>
		<!-- /.tab-pane -->
	</div>
</aside>
</div>
<!-- ./wrapper -->
<?php
include("../footer.php");
?>


<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>
<!-- InputMask -->
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.date.extensions.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.extensions.js" ?>"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo $baseurl . "plugins/ckeditor/ckeditor.js" ?>"></script>

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js" ?>"></script>

<script>
	$(function () {
		$("#prtable").DataTable();
		
		$('.select2').select2();
		
		$("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
	});



	function getsearchf(id) {
		var sub = 'sub1';
		var strURL = "search_func.php";
		$.post(strURL, { sub1: sub, id: id }, function (result) {
			$('#getsearchf').html(result);
		});
	}

	function getproject_filter(id) {
		var sub = 'sub11';
		var strURL = "search_func.php";
		$.post(strURL, { sub11: sub, id: id }, function (result) {
			$('#getproject_filter_span').html(result);
		});
	}

</script>

</body>

</html>