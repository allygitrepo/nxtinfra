<?php
session_start();
ini_set('max_execution_time', 0);

include "../dbcon.php";
require_once "../excel_libs/SimpleXLSXGen.php";

$sub = $_GET['sub'] ?? '';

// 1. User Export
if ($sub == 'user' || $sub == 'pdf' || $sub == 'list') {
	$excel_rows = [];
	$excel_rows[] = ['User List'];
	$excel_rows[] = [
		'Sr.No.',
		'User Name',
		'User Id',
		'Email Id',
		'Mobile',
		'Role',
		'Department',
		'Company',
		'Status'
	];

	// Pre-fetch roles
	$role_map = [];
	$res_r = mysqli_query($con, "SELECT id, role FROM sma_role");
	while ($rr = mysqli_fetch_array($res_r)) {
		$role_map[$rr['id']] = $rr['role'];
	}

	// Pre-fetch departments
	$dept_map = [];
	$res_d = mysqli_query($con, "SELECT id, name FROM sma_department");
	while ($rd = mysqli_fetch_array($res_d)) {
		$dept_map[$rd['id']] = $rd['name'];
	}

	// Pre-fetch companies
	$comp_map = [];
	$res_c = mysqli_query($con, "SELECT comp_id, comp_code, comp_name FROM company");
	while ($rc = mysqli_fetch_array($res_c)) {
		$comp_map[$rc['comp_id']] = !empty($rc['comp_code']) ? $rc['comp_code'] : $rc['comp_name'];
	}

	$sql = "SELECT * FROM sma_user ORDER BY username ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$i++;
		$role_id = $row['primary_role'] ?? $row['role'] ?? '';
		$role_name = $role_map[$role_id] ?? '';

		$dept_id = $row['department'] ?? '';
		$dept_name = $dept_map[$dept_id] ?? '';

		$comp_ids = explode(',', (string)($row['company_id'] ?? ''));
		$comp_names = [];
		foreach ($comp_ids as $cid) {
			$cid = trim($cid);
			if (!empty($cid) && isset($comp_map[$cid])) {
				$comp_names[] = $comp_map[$cid];
			}
		}
		$comp_name_str = implode(', ', $comp_names);

		$status = (($row['active'] ?? '') == '1') ? 'Active' : 'Inactive';

		$excel_rows[] = [
			$i,
			$row['username'] ?? '',
			$row['userid'] ?? '',
			$row['email'] ?? '',
			$row['mobile_no'] ?? '',
			$role_name,
			$dept_name,
			$comp_name_str,
			$status
		];
	}

	$xlsx = Shuchkin\SimpleXLSXGen::fromArray($excel_rows);
	$xlsx->downloadAs('user_list.xlsx');
	exit();
}

// 2. Department Export
if ($sub == 'dept') {
	$excel_rows = [];
	$excel_rows[] = ['Department List'];
	$excel_rows[] = [
		'Sr.No.',
		'Department',
		'Dept. Code'
	];

	$sql = "SELECT * FROM sma_department ORDER BY name ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$i++;
		$excel_rows[] = [
			$i,
			$row['name'] ?? '',
			$row['code'] ?? ''
		];
	}

	$xlsx = Shuchkin\SimpleXLSXGen::fromArray($excel_rows);
	$xlsx->downloadAs('department_list.xlsx');
	exit();
}

// 3. Role Export
if ($sub == 'role') {
	$excel_rows = [];
	$excel_rows[] = ['Role List'];
	$excel_rows[] = [
		'Sr.No.',
		'Role'
	];

	$sql = "SELECT * FROM sma_role ORDER BY role ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$i++;
		$excel_rows[] = [
			$i,
			$row['role'] ?? ''
		];
	}

	$xlsx = Shuchkin\SimpleXLSXGen::fromArray($excel_rows);
	$xlsx->downloadAs('role_list.xlsx');
	exit();
}

// 4. Designation Export
if ($sub == 'desig') {
	$excel_rows = [];
	$excel_rows[] = ['Designation List'];
	$excel_rows[] = [
		'Sr.No.',
		'Designation'
	];

	$sql = "SELECT * FROM sma_designation ORDER BY designation ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$i++;
		$excel_rows[] = [
			$i,
			$row['designation'] ?? ''
		];
	}

	$xlsx = Shuchkin\SimpleXLSXGen::fromArray($excel_rows);
	$xlsx->downloadAs('designation_list.xlsx');
	exit();
}

// 5. Role based Access Export
if ($sub == 'role_access') {
	$excel_rows = [];
	$excel_rows[] = ['Role Based Access List'];
	$excel_rows[] = [
		'Sr.No.',
		'Role Name'
	];

	$sql = "SELECT * FROM sma_role ORDER BY id ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$i++;
		$excel_rows[] = [
			$i,
			$row['role'] ?? ''
		];
	}

	$xlsx = Shuchkin\SimpleXLSXGen::fromArray($excel_rows);
	$xlsx->downloadAs('role_based_access_list.xlsx');
	exit();
}

// 6. Main Menu Export
if ($sub == 'main_menu') {
	$excel_rows = [];
	$excel_rows[] = ['Main Menu List'];
	$excel_rows[] = [
		'Sr.No.',
		'Order No.',
		'Main Menu',
		'Status'
	];

	$sql = "SELECT * FROM sma_main_menu ORDER BY order_no ASC, menu_name ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$i++;
		$status = (($row['status'] ?? '') == 'Y' || ($row['status'] ?? '') == '1') ? 'Active' : 'Inactive';
		$excel_rows[] = [
			$i,
			$row['order_no'] ?? '',
			$row['menu_name'] ?? '',
			$status
		];
	}

	$xlsx = Shuchkin\SimpleXLSXGen::fromArray($excel_rows);
	$xlsx->downloadAs('main_menu_list.xlsx');
	exit();
}

// 7. Sub Menu Export
if ($sub == 'sub_menu') {
	$excel_rows = [];
	$excel_rows[] = ['Sub Menu List'];
	$excel_rows[] = [
		'Sr.No.',
		'Main Menu Id',
		'Main Menu Name',
		'Sub Menu Order',
		'Sub Menu',
		'Target',
		'Status'
	];

	// Pre-fetch Main Menus
	$main_menu_map = [];
	$res_m = mysqli_query($con, "SELECT id, menu_name FROM sma_main_menu");
	while ($rm = mysqli_fetch_array($res_m)) {
		$main_menu_map[$rm['id']] = $rm['menu_name'];
	}

	$sql = "SELECT * FROM sma_menu ORDER BY menu_id ASC, order_no ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$i++;
		$mid = $row['menu_id'] ?? '';
		$mname = $main_menu_map[$mid] ?? '';
		$status = (($row['status'] ?? '') == 'Y' || ($row['status'] ?? '') == '1') ? 'Active' : 'Inactive';

		$excel_rows[] = [
			$i,
			$mid,
			$mname,
			$row['order_no'] ?? '',
			$row['sub_menu_name'] ?? '',
			$row['target'] ?? '',
			$status
		];
	}

	$xlsx = Shuchkin\SimpleXLSXGen::fromArray($excel_rows);
	$xlsx->downloadAs('sub_menu_list.xlsx');
	exit();
}

// 8. Document Type Export
if ($sub == 'document_type') {
	$excel_rows = [];
	$excel_rows[] = ['Document Type List'];
	$excel_rows[] = [
		'Sr.No.',
		'Document Type',
		'Status'
	];

	$sql = "SELECT * FROM sma_document_type ORDER BY document ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$i++;
		$status = (($row['status'] ?? '') == 'Y' || ($row['status'] ?? '') == '1') ? 'Active' : 'Inactive';
		$excel_rows[] = [
			$i,
			$row['document'] ?? '',
			$status
		];
	}

	$xlsx = Shuchkin\SimpleXLSXGen::fromArray($excel_rows);
	$xlsx->downloadAs('document_type_list.xlsx');
	exit();
}

// 9. Company Export
if ($sub == 'company') {
	$excel_rows = [];
	$excel_rows[] = ['Company List'];
	$excel_rows[] = [
		'Sr.No.',
		'Company Name',
		'Code',
		'Address',
		'GST No',
		'PAN No',
		'Email',
		'Phone'
	];

	$sql = "SELECT * FROM company ORDER BY comp_name ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$i++;
		$excel_rows[] = [
			$i,
			$row['comp_name'] ?? '',
			$row['comp_code'] ?? '',
			$row['comp_addr1'] ?? '',
			$row['comp_gst_no'] ?? '',
			$row['comp_pan_no'] ?? '',
			$row['comp_email'] ?? '',
			$row['comp_mobile'] ?? $row['comp_office'] ?? ''
		];
	}

	$xlsx = Shuchkin\SimpleXLSXGen::fromArray($excel_rows);
	$xlsx->downloadAs('company_list.xlsx');
	exit();
}

// 10. Location Export
if ($sub == 'location') {
	$excel_rows = [];
	$excel_rows[] = ['Location List'];
	$excel_rows[] = [
		'Sr.No.',
		'Location',
		'Company',
		'Address',
		'Contact No.',
		'Email'
	];

	$sql = "SELECT a.id, a.loc_name, a.loc_addr1, a.loc_contact_person_mobile, a.loc_email, b.comp_name as loc_company_name 
	        FROM sma_location a 
	        LEFT JOIN company b ON a.loc_comp_id = b.comp_id 
	        ORDER BY a.loc_name ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$i++;
		$excel_rows[] = [
			$i,
			$row['loc_name'] ?? '',
			$row['loc_company_name'] ?? '',
			$row['loc_addr1'] ?? '',
			$row['loc_contact_person_mobile'] ?? '',
			$row['loc_email'] ?? ''
		];
	}

	$xlsx = Shuchkin\SimpleXLSXGen::fromArray($excel_rows);
	$xlsx->downloadAs('location_list.xlsx');
	exit();
}

// 11. Term & Conditions Export
if ($sub == 'terms') {
	$excel_rows = [];
	$excel_rows[] = ['Term & Conditions List'];
	$excel_rows[] = [
		'Sr.No.',
		'Sequence No.',
		'Terms'
	];

	$sql = "SELECT * FROM sma_term ORDER BY order_no ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$i++;
		$excel_rows[] = [
			$i,
			$row['order_no'] ?? '',
			strip_tags($row['special_terms'] ?? '')
		];
	}

	$xlsx = Shuchkin\SimpleXLSXGen::fromArray($excel_rows);
	$xlsx->downloadAs('term_and_conditions_list.xlsx');
	exit();
}

// 12. SMTP Detail Export
if ($sub == 'smtp') {
	$excel_rows = [];
	$excel_rows[] = ['SMTP Detail List'];
	$excel_rows[] = [
		'Sr.No.',
		'Host',
		'User Name'
	];

	$sql = "SELECT * FROM smtp_dtl ORDER BY id ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$i++;
		$excel_rows[] = [
			$i,
			$row['host'] ?? '',
			$row['username'] ?? ''
		];
	}

	$xlsx = Shuchkin\SimpleXLSXGen::fromArray($excel_rows);
	$xlsx->downloadAs('smtp_detail_list.xlsx');
	exit();
}

// 13. Workflow Export
if ($sub == 'workflow') {
	$excel_rows = [];
	$excel_rows[] = ['Workflow List'];
	$excel_rows[] = [
		'Sr.No.',
		'Company',
		'Trans.Type',
		'Approver 1',
		'Approver 2',
		'Approver 3',
		'Approver 4',
		'Email Notification 1',
		'Email Notification 2',
		'Email Notification 3'
	];

	// Pre-fetch maps
	$comp_map = [];
	$res_c = mysqli_query($con, "SELECT comp_id, comp_code, comp_name FROM company");
	while ($rc = mysqli_fetch_array($res_c)) {
		$comp_map[$rc['comp_id']] = !empty($rc['comp_code']) ? $rc['comp_code'] : $rc['comp_name'];
	}

	$doc_type_map = [];
	$res_dt = mysqli_query($con, "SELECT doc_type, doc_description FROM sma_doc_type");
	while ($rdt = mysqli_fetch_array($res_dt)) {
		$doc_type_map[$rdt['doc_type']] = $rdt['doc_description'];
	}

	$wf_type_map = [];
	$wf_status_map = [];
	$res_wt = mysqli_query($con, "SELECT id, workflow_type, status FROM sma_workflow_type");
	while ($rwt = mysqli_fetch_array($res_wt)) {
		$wf_type_map[$rwt['id']] = $rwt['workflow_type'];
		$wf_status_map[$rwt['id']] = $rwt['status'];
	}

	$user_map = [];
	$res_u = mysqli_query($con, "SELECT id, username FROM sma_user");
	while ($ru = mysqli_fetch_array($res_u)) {
		$user_map[$ru['id']] = $ru['username'];
	}

	$company_id_filter = $_SESSION['company_id'] ?? '';
	$doc_type_filter   = $_SESSION['doc_type'] ?? '';
	$trans_type_filter = $_SESSION['trans_type'] ?? '';

	$sql = "SELECT * FROM sma_workflow WHERE 1";
	if (!empty($company_id_filter)) {
		$sql .= " AND company_id = '$company_id_filter'";
	}
	if (!empty($doc_type_filter)) {
		$sql .= " AND doc_type = '$doc_type_filter'";
	}
	if (!empty($trans_type_filter)) {
		$sql .= " AND trans_type = '$trans_type_filter'";
	}
	$sql .= " ORDER BY id ASC";

	$result = mysqli_query($con, $sql);
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$tt_id = $row['trans_type'] ?? '';
		if (!empty($tt_id) && isset($wf_status_map[$tt_id]) && $wf_status_map[$tt_id] == 'N') {
			continue;
		}

		$i++;
		$cname = $comp_map[$row['company_id']] ?? '';
		$dtype = $doc_type_map[$row['doc_type']] ?? ($wf_type_map[$tt_id] ?? $row['doc_type']);

		$app1 = $user_map[$row['approval_role_1']] ?? '';
		$app2 = $user_map[$row['approval_role_2']] ?? '';
		$app3 = $user_map[$row['approval_role_3']] ?? '';
		$app4 = $user_map[$row['approval_role_4']] ?? '';

		$em1 = $user_map[$row['email_one']] ?? '';
		$em2 = $user_map[$row['email_two']] ?? '';
		$em3 = $user_map[$row['email_three']] ?? '';

		$excel_rows[] = [
			$i,
			$cname,
			$dtype,
			$app1,
			$app2,
			$app3,
			$app4,
			$em1,
			$em2,
			$em3
		];
	}

	$xlsx = Shuchkin\SimpleXLSXGen::fromArray($excel_rows);
	$xlsx->downloadAs('workflow_list.xlsx');
	exit();
}

// 14. Transaction Type Export
if ($sub == 'trans_type') {
	$excel_rows = [];
	$excel_rows[] = ['Transaction Type List'];
	$excel_rows[] = [
		'Sr.No.',
		'Transaction Type',
		'Short code',
		'Status'
	];

	$sql = "SELECT * FROM sma_doc_type ORDER BY doc_description ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$i++;
		$status = (($row['status'] ?? '') == 'Y' || ($row['status'] ?? '') == '1') ? 'Active' : 'Inactive';
		$excel_rows[] = [
			$i,
			$row['doc_description'] ?? '',
			$row['doc_type'] ?? '',
			$status
		];
	}

	$xlsx = Shuchkin\SimpleXLSXGen::fromArray($excel_rows);
	$xlsx->downloadAs('transaction_type_list.xlsx');
	exit();
}

// 15. PO Doc Type Export
if ($sub == 'po_doc_type') {
	$excel_rows = [];
	$excel_rows[] = ['PO Doc Type List'];
	$excel_rows[] = [
		'Sr.No.',
		'PO Doc Type',
		'PO Doc'
	];

	$sql = "SELECT * FROM po_order_type ORDER BY po_doc_type ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$i++;
		$excel_rows[] = [
			$i,
			$row['po_doc_type'] ?? '',
			$row['po_doc_desc'] ?? ''
		];
	}

	$xlsx = Shuchkin\SimpleXLSXGen::fromArray($excel_rows);
	$xlsx->downloadAs('po_doc_type_list.xlsx');
	exit();
}

// 16. Audit Log Export
if ($sub == 'audit_log') {
	$excel_rows = [];
	$excel_rows[] = ['Audit Log List'];
	$excel_rows[] = [
		'Sr.No.',
		'Audit Date Time',
		'Company',
		'User Name',
		'Main Menu',
		'Sub Menu',
		'Details',
		'Action Taken'
	];

	// Pre-fetch companies
	$comp_map = [];
	$res_c = mysqli_query($con, "SELECT comp_id, comp_code, comp_name FROM company");
	while ($rc = mysqli_fetch_array($res_c)) {
		$comp_map[$rc['comp_id']] = !empty($rc['comp_code']) ? $rc['comp_code'] : $rc['comp_name'];
	}

	$comp_id   = $_SESSION['comp_id'] ?? '';
	$user_name = $_SESSION['user_name'] ?? '';
	$main_menu = $_SESSION['main_menu'] ?? '';
	$sub_menu  = $_SESSION['sub_menu'] ?? '';
	$action    = $_SESSION['action'] ?? '';
	$from_date = $_SESSION['from_date'] ?? '';
	$to_date   = $_SESSION['to_date'] ?? '';

	$sql = "SELECT * FROM log_tbl WHERE 1";
	if (!empty($comp_id)) {
		$sql .= " AND company_name = '$comp_id'";
	}
	if (!empty($user_name)) {
		$sql .= " AND user_name = '$user_name'";
	}
	if (!empty($main_menu)) {
		$sql .= " AND main_menu = '$main_menu'";
	}
	if (!empty($sub_menu)) {
		$sql .= " AND sub_menu = '$sub_menu'";
	}
	if (!empty($action)) {
		$sql .= " AND action = '$action'";
	}
	if (!empty($from_date) && !empty($to_date)) {
		$f_d = date('Y-m-d 00:00:00', strtotime($from_date));
		$t_d = date('Y-m-d 23:59:59', strtotime($to_date));
		$sql .= " AND audit_date_time >= '$f_d' AND audit_date_time <= '$t_d'";
	}
	$sql .= " ORDER BY id DESC";

	$result = mysqli_query($con, $sql);
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$i++;
		$cid = $row['company_name'] ?? '';
		$cname = $comp_map[$cid] ?? $cid;

		$excel_rows[] = [
			$i,
			$row['audit_date_time'] ?? '',
			$cname,
			$row['user_name'] ?? '',
			$row['main_menu'] ?? '',
			$row['sub_menu'] ?? '',
			$row['description'] ?? '',
			$row['action'] ?? ''
		];
	}

	$xlsx = Shuchkin\SimpleXLSXGen::fromArray($excel_rows);
	$xlsx->downloadAs('audit_log_list.xlsx');
	exit();
}

// 17. Financial Year Export
if ($sub == 'financial_year') {
	$excel_rows = [];
	$excel_rows[] = ['Financial Year List'];
	$excel_rows[] = [
		'Sr.No.',
		'From Date',
		'To Date',
		'Account Year',
		'PO Last Number',
		'Active'
	];

	$sql = "SELECT * FROM sma_financial_year ORDER BY id DESC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$i++;
		$f_d = !empty($row['from_date']) ? date('d-m-Y', strtotime($row['from_date'])) : '';
		$t_d = !empty($row['to_date']) ? date('d-m-Y', strtotime($row['to_date'])) : '';
		$st = !empty($row['status']) ? $row['status'] : 'N';

		$excel_rows[] = [
			$i,
			$f_d,
			$t_d,
			$row['short_fy_code'] ?? '',
			$row['po_last_number'] ?? '',
			$st
		];
	}

	$xlsx = Shuchkin\SimpleXLSXGen::fromArray($excel_rows);
	$xlsx->downloadAs('financial_year_list.xlsx');
	exit();
}

// 18. User Login Export
if ($sub == 'user_login') {
	$excel_rows = [];
	$excel_rows[] = ['User Login List'];
	$excel_rows[] = [
		'Sr.No.',
		'User',
		'Login Date Time',
		'LogOut Date Time'
	];

	// Pre-fetch users
	$user_map = [];
	$res_u = mysqli_query($con, "SELECT userid, username FROM sma_user");
	while ($ru = mysqli_fetch_array($res_u)) {
		$user_map[$ru['userid']] = $ru['username'];
	}

	$sql = "SELECT * FROM user_login WHERE 1 AND userid != '' GROUP BY userid, tdate, logout_date ORDER BY id DESC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$i++;
		$uid = $row['userid'] ?? '';
		$uname = $user_map[$uid] ?? $uid;

		$otp_date = !empty($row['otp_date']) ? date('d-m-Y h:i:sa', strtotime($row['otp_date'])) : '';
		$logout_date = !empty($row['logout_date']) ? date('d-m-Y h:i:sa', strtotime($row['logout_date'])) : '';
		$chk = !empty($row['logout_date']) ? date('d-m-Y', strtotime($row['logout_date'])) : '';
		if ($chk == '01-01-1970' || $chk == '30-11-0001' || empty($row['logout_date'])) {
			$logout_date = !empty($row['otp_date']) ? date('d-m-Y h:i:sa', strtotime('+15 minutes', strtotime($row['otp_date']))) : '';
		}

		$excel_rows[] = [
			$i,
			$uname,
			$otp_date,
			$logout_date
		];
	}

	$xlsx = Shuchkin\SimpleXLSXGen::fromArray($excel_rows);
	$xlsx->downloadAs('user_login_list.xlsx');
	exit();
}

// 19. Move Ownership Export
if ($sub == 'move_ownership') {
	$excel_rows = [];
	$excel_rows[] = ['Move Ownership / Pending Documents List'];
	$excel_rows[] = [
		'Sr.No.',
		'Form / Doc Type',
		'Doc No / ID',
		'Current Approver / Owner',
		'Date',
		'Status'
	];

	// Pre-fetch users
	$user_map = [];
	$res_u = mysqli_query($con, "SELECT id, username FROM sma_user");
	while ($ru = mysqli_fetch_array($res_u)) {
		$user_map[$ru['id']] = $ru['username'];
	}

	$modules = [
		['table' => 'sma_purchase_req', 'type' => 'Material Requisition', 'date_col' => 'date', 'approver_col' => 'approver_1', 'status_col' => 'status'],
		['table' => 'sma_approval_memo', 'type' => 'Approval Memo', 'date_col' => 'dated', 'approver_col' => 'approver_1', 'status_col' => 'status'],
		['table' => 'sma_purchase_order', 'type' => 'Purchase Order', 'date_col' => 'dated', 'approver_col' => 'approver_1', 'status_col' => 'status'],
		['table' => 'sma_supplier_invoice', 'type' => 'Supplier Invoice', 'date_col' => 'created_date', 'approver_col' => 'approver_1', 'status_col' => 'status'],
		['table' => 'sma_goods_issue_note', 'type' => 'Goods Issued Notes', 'date_col' => 'dated', 'approver_col' => 'approver_1', 'status_col' => 'status'],
		['table' => 'payment_header', 'type' => 'Payment', 'date_col' => 'dated', 'approver_col' => 'approver_1', 'status_col' => 'status'],
		['table' => 'sma_travel_expenses', 'type' => 'Operating / Travel Expense', 'date_col' => 'dated', 'approver_col' => 'approver_1', 'status_col' => 'status'],
		['table' => 'sma_traval_approval', 'type' => 'Travel Request', 'date_col' => 'dated', 'approver_col' => 'approver_1', 'status_col' => 'status']
	];

	$i = 0;
	foreach ($modules as $mod) {
		$tbl = $mod['table'];
		$type_label = $mod['type'];
		$dt_col = $mod['date_col'];
		$app_col = $mod['approver_col'];
		$st_col = $mod['status_col'];

		// Check if table exists
		$check = mysqli_query($con, "SHOW TABLES LIKE '$tbl'");
		if ($check && mysqli_num_rows($check) > 0) {
			$sql = "SELECT id, $dt_col, $app_col, $st_col FROM $tbl WHERE $st_col = 'Submitted' ORDER BY id DESC LIMIT 500";
			$res = mysqli_query($con, $sql);
			if ($res) {
				while ($row = mysqli_fetch_array($res)) {
					$i++;
					$app_id = $row[$app_col] ?? '';
					$app_name = $user_map[$app_id] ?? $app_id;
					$doc_date = !empty($row[$dt_col]) ? date('d-m-Y', strtotime($row[$dt_col])) : '';
					$st = $row[$st_col] ?? '';

					$excel_rows[] = [
						$i,
						$type_label,
						$row['id'] ?? '',
						$app_name,
						$doc_date,
						$st
					];
				}
			}
		}
	}

	$xlsx = Shuchkin\SimpleXLSXGen::fromArray($excel_rows);
	$xlsx->downloadAs('move_ownership_list.xlsx');
	exit();
}
?>
