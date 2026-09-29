<?php
session_start();
include "../dbcon.php";
include "../baseurl.php";
require_once "../excel_libs/SimpleXLSXGen.php";

$prn = "excel";
$from_date_raw = $_POST['from_date'] ?? $_SESSION['start_date'] ?? '';
$to_date_raw   = $_POST['to_date'] ?? $_SESSION['end_date'] ?? '';

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
	'PR Number',
	'Dated',
	'Req. Dated',
	'Company',
	'Location',
	'Department',
	'Subject / Reason',
	'Budget Group',
	'Budget Sub Group',
	'NOA Number',
	'PO Number',
	'By',
	'Status',
	'Pending With',
	'Remarks / Comments'
];

if (!empty($_SESSION['sqlpr'])) {
	$sql = $_SESSION['sqlpr'];
} else if (!empty($_SESSION['sqlex'])) {
	$sql = $_SESSION['sqlex'];
} else {
	$sql = "SELECT * FROM sma_purchase_req WHERE del != 'Y'";
	if (!empty($from_date) && !empty($to_date)) {
		$sql .= " AND date >= '$from_date' AND date <= '$to_date'";
	}
}

// Remove pagination limit if present
$sql = preg_replace('/\s+LIMIT\s+\d+(\s*,\s*\d+)?/i', '', $sql);
if (stripos($sql, 'order by') === false) {
	$sql .= ' ORDER BY date DESC, id DESC';
}

$result = mysqli_query($con, $sql);
$error  = mysqli_error($con);
if(!empty($error)){ echo "ERROR : " . $error; exit();}

$ln = 0;
while($row = mysqli_fetch_array($result)){
	$ln++;
	$pur_id       = $row['id'] ?? '';
	$pr_number    = $row['pr_number'] ?? '';
	$dated_raw    = $row['date'] ?? '';
	$reqdate_raw  = $row['reqDate'] ?? '';
	$company_id   = $row['company_id'] ?? '';
	$project_id   = $row['project_id'] ?? '';
	$department_id= $row['department_id'] ?? '';
	$reason       = $row['reason'] ?? '';
	$status       = $row['status'] ?? '';
	$draft_by     = $row['draft_by'] ?? '';
	$remarks      = $row['remarks'] ?? $row['comments'] ?? '';

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
	if (!empty($company_id)) {
		$sql_c = "SELECT comp_code, comp_name FROM `company` WHERE comp_id = '$company_id'";
		$res_c = mysqli_query($con, $sql_c);
		if ($res_c && $com = mysqli_fetch_array($res_c)) {
			$comp_name = !empty($com['comp_code']) ? $com['comp_code'] : ($com['comp_name'] ?? '');
		}
	}

	// Department lookup
	$department_name = '';
	if (!empty($department_id)) {
		$sql_d = "SELECT name FROM `sma_department` WHERE id = '$department_id'";
		$res_d = mysqli_query($con, $sql_d);
		if ($res_d && $deps = mysqli_fetch_array($res_d)) {
			$department_name = $deps['name'] ?? '';
		}
	}

	// Budget Group & Sub Group lookup from PR items
	$budget_name = '';
	$budget_head = '';
	if (!empty($pur_id)) {
		$sql_bi = "SELECT p.budget_head, p.budget_name 
		           FROM `sma_purchase_req_items` pri 
		           LEFT JOIN `sma_product` p ON pri.product_id = p.id 
		           WHERE pri.purchase_req_id = '$pur_id' AND (p.budget_head > 0 OR p.budget_name > 0) 
		           LIMIT 1";
		$res_bi = mysqli_query($con, $sql_bi);
		if ($res_bi && $r_bi = mysqli_fetch_array($res_bi)) {
			$b_head_id = $r_bi['budget_head'] ?? '';
			$b_name_id = $r_bi['budget_name'] ?? '';

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

	// NOA & PO links
	$ap_number = '';
	$po_number = '';
	if (!empty($pur_id)) {
		$sql_noa = "SELECT id, ap_number, status FROM `sma_approval_memo` WHERE against_indent_no = '$pur_id' AND del != 'Y' ORDER BY id DESC LIMIT 1";
		$res_noa = mysqli_query($con, $sql_noa);
		if ($res_noa && $r_noa = mysqli_fetch_array($res_noa)) {
			$against_noa_no = $r_noa['id'] ?? '';
			$ap_number = $r_noa['ap_number'] ?? '';
			if (!empty($against_noa_no)) {
				$sql_po = "SELECT po_number, status FROM `sma_purchase_order` WHERE approval_memo_ref = '$against_noa_no' AND del != 'Y' ORDER BY id DESC LIMIT 1";
				$res_po = mysqli_query($con, $sql_po);
				if ($res_po && $r_po = mysqli_fetch_array($res_po)) {
					$po_number = $r_po['po_number'] ?? '';
				}
			}
		}
	}

	// Draft By User
	$created_by_user = $draft_by;
	if (!empty($draft_by)) {
		$sql_u = "SELECT username FROM `sma_user` WHERE userid = '$draft_by' OR id = '$draft_by'";
		$res_u = mysqli_query($con, $sql_u);
		if ($res_u && $r_u = mysqli_fetch_array($res_u)) {
			$created_by_user = $r_u['username'] ?? $draft_by;
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

	$excel_rows[] = [
		$ln,
		$pr_number,
		$dated,
		$reqdate,
		$comp_name,
		$loc_name,
		$department_name,
		$reason,
		$budget_name,
		$budget_head,
		$ap_number,
		$po_number,
		$created_by_user,
		$status,
		$pending_by_name,
		$remarks
	];
}

if ($prn == 'excel') {
	$fl_name = 'Purchase_Requisition_Report_' . date('Y-m-d') . '.xlsx';
	\Shuchkin\SimpleXLSXGen::fromArray($excel_rows)->downloadAs($fl_name);
	exit();
}
?>