<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ini_set('max_execution_time', 0);

include "../dbcon.php";

$sub = $_GET['sub'] ?? '';

function export_to_excel($title, $headers, $rows, $filename_base) {
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    // Check if SimpleXLSXGen is available
    $lib_path = __DIR__ . '/../excel_libs/SimpleXLSXGen.php';
    if (file_exists($lib_path)) {
        require_once $lib_path;
        if (class_exists('\\Shuchkin\\SimpleXLSXGen') || class_exists('Shuchkin\\SimpleXLSXGen')) {
            $excel_rows = [];
            $excel_rows[] = [$title];
            $excel_rows[] = $headers;
            foreach ($rows as $r) {
                $excel_rows[] = $r;
            }
            $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($excel_rows);
            $xlsx->downloadAs($filename_base . '.xlsx');
            exit();
        }
    }
    
    // Universal Fallback: Native Excel format (works on all servers with zero dependencies)
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=" . $filename_base . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    
    echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
    echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
    echo '<style>';
    echo 'table { border-collapse: collapse; width: 100%; }';
    echo 'th, td { border: 0.5pt solid #000000; padding: 6px; font-family: Calibri, Arial, sans-serif; font-size: 11pt; }';
    echo 'th { background-color: #f2f2f2; font-weight: bold; }';
    echo '.title { font-size: 14pt; font-weight: bold; background-color: #d9edf7; text-align: center; height: 35px; }';
    echo '</style>';
    echo '</head>';
    echo '<body>';
    echo '<table border="1">';
    echo '<tr><th colspan="' . count($headers) . '" class="title">' . htmlspecialchars($title) . '</th></tr>';
    echo '<tr>';
    foreach ($headers as $h) {
        echo '<th style="text-align: left;">' . htmlspecialchars($h) . '</th>';
    }
    echo '</tr>';
    foreach ($rows as $row) {
        echo '<tr>';
        foreach ($row as $cell) {
            echo '<td style="vertical-align: top;">' . htmlspecialchars((string)$cell) . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
    echo '</body></html>';
    exit();
}

// 1. User Export
if ($sub == 'user' || $sub == 'pdf' || $sub == 'list') {
	$headers = [
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
	if ($res_r) {
		while ($rr = mysqli_fetch_array($res_r)) {
			$role_map[$rr['id']] = $rr['role'];
		}
	}

	// Pre-fetch departments
	$dept_map = [];
	$res_d = mysqli_query($con, "SELECT id, name FROM sma_department");
	if ($res_d) {
		while ($rd = mysqli_fetch_array($res_d)) {
			$dept_map[$rd['id']] = $rd['name'];
		}
	}

	// Pre-fetch companies
	$comp_map = [];
	$res_c = mysqli_query($con, "SELECT comp_id, comp_code, comp_name FROM company");
	if ($res_c) {
		while ($rc = mysqli_fetch_array($res_c)) {
			$comp_map[$rc['comp_id']] = !empty($rc['comp_code']) ? $rc['comp_code'] : $rc['comp_name'];
		}
	}

	$sql = "SELECT * FROM sma_user ORDER BY username ASC";
	$result = mysqli_query($con, $sql);
	$rows = [];
	$i = 0;
	if ($result) {
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

			$rows[] = [
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
	}

	export_to_excel('User List', $headers, $rows, 'user_list');
}

// 2. Department Export
if ($sub == 'dept') {
	$headers = [
		'Sr.No.',
		'Department',
		'Dept. Code'
	];

	$sql = "SELECT * FROM sma_department ORDER BY name ASC";
	$result = mysqli_query($con, $sql);
	$rows = [];
	$i = 0;
	if ($result) {
		while ($row = mysqli_fetch_array($result)) {
			$i++;
			$rows[] = [
				$i,
				$row['name'] ?? '',
				$row['code'] ?? ''
			];
		}
	}

	export_to_excel('Department List', $headers, $rows, 'department_list');
}

// 3. Role Export
if ($sub == 'role') {
	$headers = [
		'Sr.No.',
		'Role'
	];

	$sql = "SELECT * FROM sma_role ORDER BY role ASC";
	$result = mysqli_query($con, $sql);
	$rows = [];
	$i = 0;
	if ($result) {
		while ($row = mysqli_fetch_array($result)) {
			$i++;
			$rows[] = [
				$i,
				$row['role'] ?? ''
			];
		}
	}

	export_to_excel('Role List', $headers, $rows, 'role_list');
}

// 4. Designation Export
if ($sub == 'desig') {
	$headers = [
		'Sr.No.',
		'Designation'
	];

	$sql = "SELECT * FROM sma_designation ORDER BY designation ASC";
	$result = mysqli_query($con, $sql);
	$rows = [];
	$i = 0;
	if ($result) {
		while ($row = mysqli_fetch_array($result)) {
			$i++;
			$rows[] = [
				$i,
				$row['designation'] ?? ''
			];
		}
	}

	export_to_excel('Designation List', $headers, $rows, 'designation_list');
}

// 5. Role based Access Export
if ($sub == 'role_access') {
	$headers = [
		'Sr.No.',
		'Role Name'
	];

	$sql = "SELECT * FROM sma_role ORDER BY id ASC";
	$result = mysqli_query($con, $sql);
	$rows = [];
	$i = 0;
	if ($result) {
		while ($row = mysqli_fetch_array($result)) {
			$i++;
			$rows[] = [
				$i,
				$row['role'] ?? ''
			];
		}
	}

	export_to_excel('Role Based Access List', $headers, $rows, 'role_based_access_list');
}
?>
