<?php
session_start();
ini_set('max_execution_time', 0);

if($_GET['sub'] == 'exp'){
	include "../dbcon.php";
	require_once "../excel_libs/SimpleXLSXGen.php";

	$excel_rows = [];
	$excel_rows[] = ['Account Master List'];
	$excel_rows[] = [
		'Sr.No.',
		'Account Name',
		'Account Group',
		'Company',
		'Account Type',
		'Percentage',
		'CC Code',
		'Status'
	];

	// Pre-fetch maps
	$ag_map = [];
	$res_ag = mysqli_query($con, "SELECT id, account_group FROM sma_account_group");
	while ($rag = mysqli_fetch_array($res_ag)) {
		$ag_map[$rag['id']] = $rag['account_group'];
	}

	$comp_map = [];
	$res_c = mysqli_query($con, "SELECT comp_id, comp_code, comp_name FROM company");
	while ($rc = mysqli_fetch_array($res_c)) {
		$comp_map[$rc['comp_id']] = !empty($rc['comp_code']) ? $rc['comp_code'] : $rc['comp_name'];
	}

	$type_map = [
		'A' => 'Purchase',
		'B' => 'Bank',
		'C' => 'Cash',
		'D' => 'Deduction',
		'E' => 'Expense',
		'I' => 'Income',
		'L' => 'Liability',
		'T' => 'Tax'
	];

	$account_type_filter = $_SESSION['account_type'] ?? '';
	$search_filter       = $_SESSION['search'] ?? '';

	$sql = "SELECT * FROM account_mst WHERE (del != 'Y' OR del IS NULL OR del = '')";
	if (!empty($account_type_filter)) {
		$sql .= " AND account_type = '$account_type_filter'";
	}
	if (!empty($search_filter)) {
		$sql .= " AND (account_name LIKE '%$search_filter%' OR budget_code LIKE '%$search_filter%')";
	}
	$sql .= " ORDER BY account_name ASC";

	$result = mysqli_query($con, $sql);
	$i = 0;
	while($row = mysqli_fetch_array($result)){
		$i++;
		$acc_name   = $row['account_name'] ?? '';
		$acc_group  = $ag_map[$row['account_group']] ?? '';
		$comp_name  = $comp_map[$row['company_id']] ?? '';
		$raw_type   = $row['account_type'] ?? '';
		$acc_type   = $type_map[$raw_type] ?? $raw_type;
		$percentage = floatval($row['percentage'] ?? 0);
		$cc_code    = $row['budget_code'] ?? '';
		$st_raw     = $row['status'] ?? '';
		$status_txt = ($st_raw == 'Y' || $st_raw == '1' || strtolower($st_raw) == 'active') ? 'Active' : 'Inactive';

		$excel_rows[] = [
			$i,
			$acc_name,
			$acc_group,
			$comp_name,
			$acc_type,
			$percentage,
			$cc_code,
			$status_txt
		];
	}

	$fl_name = 'Account_Master_List.xlsx';
	\Shuchkin\SimpleXLSXGen::fromArray($excel_rows)->downloadAs($fl_name);
	exit();
}

if($_GET['sub'] == 'accgrp'){
	include "../dbcon.php";
	require_once "../excel_libs/SimpleXLSXGen.php";

	$excel_rows = [];
	$excel_rows[] = ['Account Group List'];
	$excel_rows[] = [
		'Sr.No.',
		'Account Group'
	];

	$sql = "SELECT * FROM sma_account_group ORDER BY account_group ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while($row = mysqli_fetch_array($result)){
		$i++;
		$excel_rows[] = [
			$i,
			$row['account_group'] ?? ''
		];
	}

	$fl_name = 'Account_Group_List.xlsx';
	\Shuchkin\SimpleXLSXGen::fromArray($excel_rows)->downloadAs($fl_name);
	exit();
}

if($_GET['sub'] == 'gst'){
	include "../dbcon.php";
	require_once "../excel_libs/SimpleXLSXGen.php";

	$excel_rows = [];
	$excel_rows[] = ['GST Master List'];
	$excel_rows[] = [
		'Sr.No.',
		'TAX Description',
		'GST % Rate',
		'SGST Account Name',
		'CGST Account Name',
		'IGST Account Name',
		'Status'
	];

	// Pre-fetch account master map
	$ac_map = [];
	$res_ac = mysqli_query($con, "SELECT id, account_name FROM account_mst");
	while ($rac = mysqli_fetch_array($res_ac)) {
		$ac_map[$rac['id']] = $rac['account_name'];
	}

	$sql = "SELECT * FROM gst_mst ORDER BY id ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while($row = mysqli_fetch_array($result)){
		$i++;
		$gst_name  = $row['gst_name'] ?? '';
		$igst_rate = $row['igst'] ?? '';
		$sgst_name = $ac_map[$row['sgst_account_id']] ?? '';
		$cgst_name = $ac_map[$row['cgst_account_id']] ?? '';
		$igst_name = $ac_map[$row['igst_account_id']] ?? '';
		$st_raw    = $row['status'] ?? '';
		$status_txt= ($st_raw == 'Y' || $st_raw == '1' || strtolower($st_raw) == 'active') ? 'Active' : 'Inactive';

		$excel_rows[] = [
			$i,
			$gst_name,
			$igst_rate,
			$sgst_name,
			$cgst_name,
			$igst_name,
			$status_txt
		];
	}

	$fl_name = 'GST_Master_List.xlsx';
	\Shuchkin\SimpleXLSXGen::fromArray($excel_rows)->downloadAs($fl_name);
	exit();
}
?>
