<?php
if(isset($_GET['sub']) && $_GET['sub'] == 'pdf'){
	session_start();
	include "../dbcon.php";
	include "../baseurl.php";
	require_once "../excel_libs/SimpleXLSXGen.php";

	$prn = "excel";
	$from_date_raw = $_POST['from_date'] ?? $_SESSION['start_date'] ?? '';
	$to_date_raw   = $_POST['to_date'] ?? $_SESSION['end_date'] ?? '';
	$department_id = $_POST['department'] ?? '';
	$company_id    = $_POST['company_id'] ?? '';

	$from_date = (!empty($from_date_raw) && $from_date_raw != '1970-01-01' && $from_date_raw != '0000-00-00') ? date('Y-m-d', strtotime($from_date_raw)) : '';
	$to_date   = (!empty($to_date_raw) && $to_date_raw != '1970-01-01' && $to_date_raw != '0000-00-00') ? date('Y-m-d', strtotime($to_date_raw)) : '';

	$from_date_dmy = (!empty($from_date_raw) && $from_date_raw != '1970-01-01' && $from_date_raw != '0000-00-00') ? date('d-m-Y', strtotime($from_date_raw)) : '';
	$to_date_dmy   = (!empty($to_date_raw) && $to_date_raw != '1970-01-01' && $to_date_raw != '0000-00-00') ? date('d-m-Y', strtotime($to_date_raw)) : '';

	if (!empty($from_date_dmy) && !empty($to_date_dmy)) {
		$title_banner = 'Purchase Requisition Register from ' . $from_date_dmy . ' TO ' . $to_date_dmy;
	} else if (!empty($from_date_dmy)) {
		$title_banner = 'Purchase Requisition Register from ' . $from_date_dmy;
	} else if (!empty($to_date_dmy)) {
		$title_banner = 'Purchase Requisition Register up to ' . $to_date_dmy;
	} else {
		$title_banner = 'Purchase Requisition Register';
	}

	$excel_rows = [];
	$excel_rows[] = [$title_banner];
	$excel_rows[] = [
		'Sr.No.',
		'Company',
		'Location',
		'Department',
		'PR.Number',
		'Dated',
		'Req.Dated',
		'Delivery Loc.',
		'Reason / Subject',
		'Item Sr.No.',
		'Material / Description',
		'Unit',
		'Qty.',
		'Budget Group',
		'Budget Sub Group',
		'Status',
		'Pending With'
	];

	$sql = "SELECT * FROM sma_purchase_req WHERE del != 'Y'";
	if (!empty($from_date) && !empty($to_date)) {
		$sql .= " AND date >= '$from_date' AND date <= '$to_date'";
	}
	if (!empty($company_id)) {
		$sql .= " AND company_id = '$company_id'";
	}
	if (!empty($department_id)) {
		$sql .= " AND department_id = '$department_id'";
	}
	$sql .= " ORDER BY date DESC, id DESC";

	$result = mysqli_query($con, $sql);
	$error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}

	$ln = 0;
	while($row = mysqli_fetch_array($result)){
		$ln++;
		$pur_id               = $row['id'] ?? '';
		$pr_number            = $row['pr_number'] ?? '';
		$dated_raw            = $row['date'] ?? '';
		$reqdate_raw          = $row['reqDate'] ?? '';
		$comp_id              = $row['company_id'] ?? '';
		$dept_id              = $row['department_id'] ?? '';
		$delivery_location_id = $row['delivery_location_id'] ?? '';
		$project_id           = $row['project_id'] ?? '';
		$reason               = $row['reason'] ?? '';
		$status               = $row['status'] ?? '';

		$dated   = (!empty($dated_raw) && $dated_raw != '1970-01-01' && $dated_raw != '0000-00-00') ? date('d-m-Y', strtotime($dated_raw)) : '';
		$reqdate = (!empty($reqdate_raw) && $reqdate_raw != '1970-01-01' && $reqdate_raw != '0000-00-00') ? date('d-m-Y', strtotime($reqdate_raw)) : '';

		// Location lookup
		$loc_name = '';
		if (!empty($project_id)) {
			$sql_l = "SELECT loc_name FROM `sma_location` WHERE id = '$project_id'";
			$res_l = mysqli_query($con, $sql_l);
			if ($res_l && $lc = mysqli_fetch_array($res_l)) {
				$loc_name = $lc['loc_name'] ?? '';
			}
		}

		// Company lookup
		$comp_name = '';
		if (!empty($comp_id)) {
			$sql_c = "SELECT comp_code, comp_name FROM `company` WHERE comp_id = '$comp_id'";
			$res_c = mysqli_query($con, $sql_c);
			if ($res_c && $com = mysqli_fetch_array($res_c)) {
				$comp_name = !empty($com['comp_code']) ? $com['comp_code'] : ($com['comp_name'] ?? '');
			}
		}

		// Department lookup
		$dept_name = '';
		if (!empty($dept_id)) {
			$sql_d = "SELECT name FROM `sma_department` WHERE id = '$dept_id'";
			$res_d = mysqli_query($con, $sql_d);
			if ($res_d && $deps = mysqli_fetch_array($res_d)) {
				$dept_name = $deps['name'] ?? '';
			}
		}

		// Pending Approver
		$pending_by = '';
		for ($i = 1; $i <= 8; $i++) {
			if (($row['approver_' . $i . '_status'] ?? '') == 'Submitted') {
				$pending_by = $row['approver_' . $i] ?? '';
				break;
			}
		}
		$pending_by_name = '';
		if (!empty($pending_by)) {
			$sql_p = "SELECT username FROM `sma_user` WHERE id = '$pending_by' OR userid = '$pending_by'";
			$res_p = mysqli_query($con, $sql_p);
			if ($res_p && $r_p = mysqli_fetch_array($res_p)) {
				$pending_by_name = 'To ' . ($r_p['username'] ?? '');
			}
		}

		// Line items
		$sql_items = "SELECT * FROM `sma_purchase_req_items` WHERE purchase_req_id = '$pur_id'";
		$res_items = mysqli_query($con, $sql_items);
		$has_items = false;
		$item_idx = 0;

		if ($res_items && mysqli_num_rows($res_items) > 0) {
			while($rw = mysqli_fetch_array($res_items)) {
				$has_items = true;
				$item_idx++;
				$product_id  = $rw['product_id'] ?? '';
				$description = $rw['description'] ?? '';
				$qty         = (float)($rw['quantity'] ?? 0);
				$unit        = $rw['unit'] ?? '';

				$budget_name = '';
				$budget_head = '';

				if (!empty($product_id)) {
					$sql_pr = "SELECT name, uom, budget_head, budget_name FROM `sma_product` WHERE id = '$product_id'";
					$res_pr = mysqli_query($con, $sql_pr);
					if ($res_pr && $r_pr = mysqli_fetch_array($res_pr)) {
						if (!empty($r_pr['name'])) {
							$description = $r_pr['name'] . (!empty($description) ? ' - ' . $description : '');
						}
						if (empty($unit)) {
							$unit = $r_pr['uom'] ?? '';
						}

						$b_head_id = $r_pr['budget_head'] ?? '';
						$b_name_id = $r_pr['budget_name'] ?? '';

						if (!empty($b_head_id)) {
							$sql_sub = "SELECT budget_head, budget_name FROM `sma_budget_subgroup` WHERE id = '$b_head_id'";
							$res_sub = mysqli_query($con, $sql_sub);
							if ($res_sub && $r_sub = mysqli_fetch_array($res_sub)) {
								$budget_head = $r_sub['budget_head'] ?? '';
								if (empty($b_name_id) && !empty($r_sub['budget_name'])) {
									$b_name_id = $r_sub['budget_name'];
								}
							}
						}

						if (!empty($b_name_id)) {
							$sql_bn = "SELECT name FROM `sma_budget_name` WHERE id = '$b_name_id'";
							$res_bn = mysqli_query($con, $sql_bn);
							if ($res_bn && $r_bn = mysqli_fetch_array($res_bn)) {
								$budget_name = $r_bn['name'] ?? '';
							}
						}
					}
				}

				$excel_rows[] = [
					$ln,
					$comp_name,
					$loc_name,
					$dept_name,
					$pr_number,
					$dated,
					$reqdate,
					$delivery_location_id,
					$reason,
					$item_idx,
					$description,
					$unit,
					$qty,
					$budget_name,
					$budget_head,
					$status,
					$pending_by_name
				];
			}
		}

		if (!$has_items) {
			$excel_rows[] = [
				$ln,
				$comp_name,
				$loc_name,
				$dept_name,
				$pr_number,
				$dated,
				$reqdate,
				$delivery_location_id,
				$reason,
				'',
				'',
				'',
				'',
				'',
				'',
				$status,
				$pending_by_name
			];
		}
	}

	if ($prn == 'excel') {
		$fl_name = 'Purchase_Requisition_Item_Register_' . date('Y-m-d') . '.xlsx';
		\Shuchkin\SimpleXLSXGen::fromArray($excel_rows)->downloadAs($fl_name);
		exit();
	}
}
?>