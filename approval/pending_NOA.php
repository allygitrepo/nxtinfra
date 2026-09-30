<?php
ini_set('max_execution_time', 0);
session_start();

if(isset($_GET['sub']) && $_GET['sub'] == 'pdf'){
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
		$title_banner = 'Pending NOA Register from ' . $from_date_dmy . ' TO ' . $to_date_dmy;
	} else if (!empty($from_date_dmy)) {
		$title_banner = 'Pending NOA Register from ' . $from_date_dmy;
	} else if (!empty($to_date_dmy)) {
		$title_banner = 'Pending NOA Register up to ' . $to_date_dmy;
	} else {
		$title_banner = 'Pending NOA Register';
	}

	$excel_rows = [];
	$excel_rows[] = [$title_banner];
	$excel_rows[] = [
		'Sr.No.',
		'SPV Name',
		'Department',
		'Location',
		'NOA No.',
		'Dated',
		'Budget Group',
		'Budget Sub Group',
		'Budget Code',
		'Supplier Name',
		'Description',
		'Amount',
		'Created By',
		'Pending for Approval to',
		'Status'
	];

	// Pre-fetch Master Maps for ultra-fast in-memory resolution
	$comp_map = [];
	$res_c = mysqli_query($con, "SELECT comp_id, comp_code, comp_name FROM `company`");
	while ($rc = mysqli_fetch_array($res_c)) {
		$comp_map[$rc['comp_id']] = !empty($rc['comp_code']) ? $rc['comp_code'] : $rc['comp_name'];
	}

	$dept_map = [];
	$res_d = mysqli_query($con, "SELECT id, name FROM `sma_department`");
	while ($rd = mysqli_fetch_array($res_d)) {
		$dept_map[$rd['id']] = $rd['name'];
	}

	$loc_map = [];
	$comp_loc_map = [];
	$res_l = mysqli_query($con, "SELECT id, loc_name, loc_comp_id FROM `sma_location`");
	while ($rl = mysqli_fetch_array($res_l)) {
		$loc_map[$rl['id']] = $rl['loc_name'];
		if (!empty($rl['loc_comp_id'])) {
			$comp_loc_map[$rl['loc_comp_id']] = $rl['loc_name'];
		}
	}
	$res_l2 = mysqli_query($con, "SELECT id, location FROM `location`");
	while ($rl2 = mysqli_fetch_array($res_l2)) {
		if (empty($loc_map[$rl2['id']])) {
			$loc_map[$rl2['id']] = $rl2['location'];
		}
	}

	$comp_city_map = [];
	$res_cc = mysqli_query($con, "SELECT comp_id, comp_city FROM `company`");
	while ($rcc = mysqli_fetch_array($res_cc)) {
		if (!empty($rcc['comp_city'])) {
			$comp_city_map[$rcc['comp_id']] = $rcc['comp_city'];
		}
	}

	$user_map = [];
	$res_u = mysqli_query($con, "SELECT id, userid, username FROM `sma_user`");
	while ($ru = mysqli_fetch_array($res_u)) {
		$user_map[$ru['id']] = $ru['username'];
		if (!empty($ru['userid'])) {
			$user_map[$ru['userid']] = $ru['username'];
		}
	}

	$party_map = [];
	$res_p = mysqli_query($con, "SELECT id, party_name FROM `sma_party_mst`");
	while ($rp = mysqli_fetch_array($res_p)) {
		$party_map[$rp['id']] = $rp['party_name'];
	}

	$bn_map = [];
	$res_bn = mysqli_query($con, "SELECT id, name FROM `sma_budget_name`");
	while ($rbn = mysqli_fetch_array($res_bn)) {
		$bn_map[$rbn['id']] = $rbn['name'];
	}

	$bs_map = [];
	$res_bs = mysqli_query($con, "SELECT id, budget_head, budget_name, budget_code FROM `sma_budget_subgroup`");
	while ($rbs = mysqli_fetch_array($res_bs)) {
		$bs_map[$rbs['id']] = [
			'head' => $rbs['budget_head'],
			'name_id' => $rbs['budget_name'],
			'code' => $rbs['budget_code']
		];
	}

	$prod_map = [];
	$res_pr = mysqli_query($con, "SELECT id, name, budget_head, budget_name, budget_code FROM `sma_product`");
	while ($rpr = mysqli_fetch_array($res_pr)) {
		$prod_map[$rpr['id']] = [
			'name' => $rpr['name'],
			'head' => $rpr['budget_head'],
			'name_id' => $rpr['budget_name'],
			'code' => $rpr['budget_code']
		];
	}

	$bgt_map = [];
	$res_bg = mysqli_query($con, "SELECT id, budget_head, budget_name, budget_code FROM `sma_budget`");
	while ($rbg = mysqli_fetch_array($res_bg)) {
		$bgt_map[$rbg['id']] = [
			'head_id' => $rbg['budget_head'],
			'name_id' => $rbg['budget_name'],
			'code' => $rbg['budget_code']
		];
	}

	// PR Map
	$pr_map = [];
	$res_prq = mysqli_query($con, "SELECT id, pr_number, company_id, department_id, delivery_location_id FROM `sma_purchase_req`");
	while ($rprq = mysqli_fetch_array($res_prq)) {
		$pinfo = [
			'comp' => $rprq['company_id'],
			'dept' => $rprq['department_id'],
			'loc' => $rprq['delivery_location_id']
		];
		$pr_map[$rprq['id']] = $pinfo;
		if (!empty($rprq['pr_number'])) {
			$pr_map[$rprq['pr_number']] = $pinfo;
		}
	}

	// PR Items Map (product_id)
	$pr_items_map = [];
	$res_pri = mysqli_query($con, "SELECT purchase_req_id, product_id FROM `sma_purchase_req_items` WHERE product_id > 0");
	while ($rpri = mysqli_fetch_array($res_pri)) {
		if (empty($pr_items_map[$rpri['purchase_req_id']])) {
			$pr_items_map[$rpri['purchase_req_id']] = $rpri['product_id'];
		}
	}

	// Approval Items Map
	$ap_items_map = [];
	$res_ai = mysqli_query($con, "SELECT approval_hdr_id, product_id, budget_id, product_name, product_desc FROM `sma_approval_items`");
	while ($rai = mysqli_fetch_array($res_ai)) {
		if (empty($ap_items_map[$rai['approval_hdr_id']])) {
			$ap_items_map[$rai['approval_hdr_id']] = [
				'product_id' => $rai['product_id'],
				'budget_id' => $rai['budget_id'],
				'name' => $rai['product_name'],
				'desc' => $rai['product_desc']
			];
		}
	}

	// Approval Details (Vendor) Map
	$ap_details_map = [];
	$res_ad = mysqli_query($con, "SELECT approval_hdr_id, supplier_name, `values`, vendor_selected FROM `sma_approval_details` ORDER BY approval_hdr_id ASC, approval_srno ASC");
	while ($rad = mysqli_fetch_array($res_ad)) {
		$hid = $rad['approval_hdr_id'];
		$s_id = $rad['supplier_name'];
		$s_name = $party_map[$s_id] ?? $s_id;
		$val = floatval($rad['values'] ?? 0);
		if (empty($ap_details_map[$hid]) || ($rad['vendor_selected'] ?? '') == 'Y') {
			$ap_details_map[$hid] = [
				'supplier_name' => $s_name,
				'amount' => $val
			];
		}
	}

	if (!empty($_SESSION['sqlex'])) {
		$sql = $_SESSION['sqlex'];
	} else {
		$sql = "SELECT * FROM sma_approval_memo WHERE (del != 'Y' OR del IS NULL OR del = '')";
		if (!empty($from_date) && !empty($to_date)) {
			$sql .= " AND dated >= '$from_date' AND dated <= '$to_date'";
		}
		if (!empty($company_id)) {
			$sql .= " AND (company = '$company_id' OR project = '$company_id')";
		}
		if (!empty($department_id)) {
			$sql .= " AND department = '$department_id'";
		}
	}

	// Remove pagination limit if present
	$sql = preg_replace('/\s+LIMIT\s+\d+(\s*,\s*\d+)?/i', '', $sql);
	if (stripos($sql, 'order by') === false) {
		$sql .= ' ORDER BY dated DESC, id DESC';
	}

	$result = mysqli_query($con, $sql);
	$error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}

	$ln = 0;
	while($row = mysqli_fetch_array($result)){
		$ln++;
		$app_id            = $row['id'] ?? '';
		$ap_number         = !empty($row['ap_number']) ? $row['ap_number'] : ($row['memo_number'] ?? '');
		$dated_raw         = $row['dated'] ?? '';
		$company_id_row    = $row['company'] ?? '';
		$department_id_row = $row['department'] ?? '';
		$location_id_row   = $row['location'] ?? '';
		$project_id_row    = $row['project'] ?? '';
		$against_indent_no = $row['against_indent_no'] ?? '';
		$memo_budget_head  = $row['budget_head'] ?? '';
		$memo_budget_name  = $row['budget_name'] ?? '';
		$subject           = $row['subject'] ?? '';
		$status            = $row['status'] ?? '';
		$cost              = floatval($row['cost'] ?? 0);
		$draft_by          = $row['draft_by'] ?? '';

		$dated = (!empty($dated_raw) && $dated_raw != '1970-01-01' && $dated_raw != '0000-00-00') ? date('d-m-Y', strtotime($dated_raw)) : '';

		// Linked PR data
		$pr_info = $pr_map[$against_indent_no] ?? [];
		$pr_comp = $pr_info['comp'] ?? '';
		$pr_dept = $pr_info['dept'] ?? '';
		$pr_loc  = $pr_info['loc'] ?? '';

		// Draft By
		$draft_by_name = $user_map[$draft_by] ?? $draft_by;

		// Pending Approver
		$pending_by_name = '';
		for ($i = 1; $i <= 8; $i++) {
			if (($row['approver_' . $i . '_status'] ?? '') == 'Submitted') {
				$p_id = $row['approver_' . $i] ?? '';
				if (!empty($p_id)) {
					$pending_by_name = $user_map[$p_id] ?? '';
				}
				break;
			}
		}
		if (empty($pending_by_name) && !empty($row['current_approver'])) {
			$pending_by_name = $user_map[$row['current_approver']] ?? '';
		}

		// Company
		$c_id = !empty($company_id_row) ? $company_id_row : (!empty($project_id_row) ? $project_id_row : $pr_comp);
		$comp_name = $comp_map[$c_id] ?? '';

		// Department
		$d_id = !empty($department_id_row) ? $department_id_row : $pr_dept;
		$department_name = $dept_map[$d_id] ?? '';

		// Location
		$l_id = !empty($location_id_row) ? $location_id_row : $pr_loc;
		$loc_name = $loc_map[$l_id] ?? '';
		if (empty($loc_name) && !empty($c_id)) {
			$loc_name = $comp_loc_map[$c_id] ?? ($comp_city_map[$c_id] ?? '');
		}

		// Item & Budget Resolution
		$budget_name = '';
		$budget_head = '';
		$budget_code = '';
		$item_info = $ap_items_map[$app_id] ?? [];
		$item_prod_id = $item_info['product_id'] ?? 0;
		$item_budget_id = $item_info['budget_id'] ?? 0;

		// 1. From sma_budget via budget_id
		if (!empty($item_budget_id) && isset($bgt_map[$item_budget_id])) {
			$bg = $bgt_map[$item_budget_id];
			$budget_code = $bg['code'];
			$h_id = $bg['head_id'];
			$n_id = $bg['name_id'];
			if (isset($bs_map[$h_id])) {
				$budget_head = $bs_map[$h_id]['head'];
				if (empty($budget_code)) $budget_code = $bs_map[$h_id]['code'];
				if (empty($n_id)) $n_id = $bs_map[$h_id]['name_id'];
			}
			if (isset($bn_map[$n_id])) {
				$budget_name = $bn_map[$n_id];
			}
		}

		// 2. From product master via product_id
		$effective_prod_id = $item_prod_id > 0 ? $item_prod_id : ($pr_items_map[$against_indent_no] ?? 0);
		if ((empty($budget_name) || empty($budget_head)) && $effective_prod_id > 0 && isset($prod_map[$effective_prod_id])) {
			$pr = $prod_map[$effective_prod_id];
			$p_head = $pr['head'];
			$p_name = $pr['name_id'];
			if (empty($budget_code)) $budget_code = $pr['code'];

			if (!empty($p_head)) {
				if (isset($bs_map[$p_head])) {
					if (empty($budget_head)) $budget_head = $bs_map[$p_head]['head'];
					if (empty($budget_code)) $budget_code = $bs_map[$p_head]['code'];
					if (empty($p_name)) $p_name = $bs_map[$p_head]['name_id'];
				} else {
					if (empty($budget_head)) $budget_head = $p_head;
				}
			}
			if (!empty($p_name)) {
				if (isset($bn_map[$p_name])) {
					if (empty($budget_name)) $budget_name = $bn_map[$p_name];
				} else {
					if (empty($budget_name)) $budget_name = $p_name;
				}
			}
		}

		// 3. Fallback from Memo header
		if ((empty($budget_name) || empty($budget_head)) && !empty($memo_budget_head)) {
			if (isset($bs_map[$memo_budget_head])) {
				if (empty($budget_head)) $budget_head = $bs_map[$memo_budget_head]['head'];
				if (empty($budget_code)) $budget_code = $bs_map[$memo_budget_head]['code'];
				$m_name = $bs_map[$memo_budget_head]['name_id'];
				if (!empty($m_name) && isset($bn_map[$m_name])) {
					if (empty($budget_name)) $budget_name = $bn_map[$m_name];
				}
			} else {
				if (empty($budget_head)) $budget_head = $memo_budget_head;
			}
		}
		if (empty($budget_name) && !empty($memo_budget_name)) {
			$budget_name = $bn_map[$memo_budget_name] ?? $memo_budget_name;
		}

		// Supplier & Amount
		$vendor_info = $ap_details_map[$app_id] ?? [];
		$supplier_name = $vendor_info['supplier_name'] ?? '';
		$quoted_amount = $vendor_info['amount'] ?? 0;
		if ($quoted_amount == 0 && $cost > 0) {
			$quoted_amount = $cost;
		}

		$excel_rows[] = [
			$ln,
			$comp_name,
			$department_name,
			$loc_name,
			$ap_number,
			$dated,
			$budget_name,
			$budget_head,
			$budget_code,
			$supplier_name,
			$subject,
			$quoted_amount,
			$draft_by_name,
			$pending_by_name,
			$status
		];
	}

	if ($prn == 'excel') {
		$fl_name = 'Pending_NOA_Report_' . date('Y-m-d') . '.xlsx';
		\Shuchkin\SimpleXLSXGen::fromArray($excel_rows)->downloadAs($fl_name);
		exit();
	}
}
?>
