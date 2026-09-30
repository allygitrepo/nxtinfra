<?php
session_start();
ini_set('max_execution_time', 0);

if($_GET['sub'] == 'list'){
	include("../dbcon.php");
	require_once "../excel_libs/SimpleXLSXGen.php";

	$excel_rows = [];
	$excel_rows[] = ['Product Master'];
	$excel_rows[] = [
		'Sr.No.',
		'Product Name',
		'Product Group',
		'Budget Group',
		'Budget Head',
		'Category',
		'UOM',
		'PO Threshold',
		'Tolerance Level',
		'GST Type',
		'HSN Code',
		'Active'
	];

	// Pre-fetch maps
	$pg_map = [];
	$res_pg = mysqli_query($con, "SELECT id, product_group FROM sma_product_group");
	while ($rpg = mysqli_fetch_array($res_pg)) {
		$pg_map[$rpg['id']] = $rpg['product_group'];
	}

	$bn_map = [];
	$res_bn = mysqli_query($con, "SELECT id, name FROM sma_budget_name");
	while ($rbn = mysqli_fetch_array($res_bn)) {
		$bn_map[$rbn['id']] = $rbn['name'];
	}

	$bs_map = [];
	$res_bs = mysqli_query($con, "SELECT id, budget_head, budget_name, budget_code FROM sma_budget_subgroup");
	while ($rbs = mysqli_fetch_array($res_bs)) {
		$bs_map[$rbs['id']] = [
			'head' => $rbs['budget_head'],
			'name_id' => $rbs['budget_name']
		];
	}

	$gst_map = [];
	$res_gst = mysqli_query($con, "SELECT id, gst_name FROM gst_mst");
	while ($rgst = mysqli_fetch_array($res_gst)) {
		$gst_map[$rgst['id']] = $rgst['gst_name'];
	}

	$search        = $_SESSION['search'] ?? '';
	$product_group = $_SESSION['product_group'] ?? '';
	$category_fltr = $_SESSION['category'] ?? '';

	$sql = "SELECT * FROM sma_product WHERE 1";
	if (!empty($product_group)) {
		$sql .= " AND product_group = '$product_group'";
	}
	if (!empty($search)) {
		$sql .= " AND name LIKE '%$search%'";
	}
	if (!empty($category_fltr)) {
		$sql .= " AND category = '$category_fltr'";
	}
	$sql .= " ORDER BY vertical_type, product_group, category, name";

	$result = mysqli_query($con, $sql);
	$i = 0;
	while($row = mysqli_fetch_array($result)){
		$i++;
		$name            = $row['name'] ?? '';
		$group_id        = $row['product_group'] ?? '';
		$group_name      = $pg_map[$group_id] ?? $group_id;

		$bgt_name_id     = $row['budget_name'] ?? 0;
		$bgt_head_id     = $row['budget_head'] ?? 0;

		$budget_head_val = '';
		$budget_group_val = '';

		if (!empty($bgt_head_id) && isset($bs_map[$bgt_head_id])) {
			$budget_head_val = $bs_map[$bgt_head_id]['head'];
			if (empty($bgt_name_id)) {
				$bgt_name_id = $bs_map[$bgt_head_id]['name_id'];
			}
		}
		if (!empty($bgt_name_id) && isset($bn_map[$bgt_name_id])) {
			$budget_group_val = $bn_map[$bgt_name_id];
		}

		$category_val    = $row['category'] ?? '';
		$category_text   = ($category_val == 'M') ? 'Material' : (($category_val == 'S') ? 'Service' : $category_val);
		$uom             = $row['uom'] ?? '';
		$po_threshold    = $row['po_threashold'] ?? '';
		if ($po_threshold == 'Q') {
			$po_thresh_text = 'Qty';
		} else if ($po_threshold == 'V') {
			$po_thresh_text = 'Value';
		} else {
			$po_thresh_text = '';
		}
		$tolerance_level = $row['tolerance_level'] ?? '';
		$gst_id          = $row['gst_type'] ?? '';
		$gst_name        = $gst_map[$gst_id] ?? $gst_id;
		$hsn_code        = $row['hsn_code'] ?? '';
		$active_raw      = $row['active'] ?? '';
		$active_text     = ($active_raw == 'N' || $active_raw == '0' || strtolower($active_raw) == 'no') ? 'No' : 'Yes';

		$excel_rows[] = [
			$i,
			$name,
			$group_name,
			$budget_group_val,
			$budget_head_val,
			$category_text,
			$uom,
			$po_thresh_text,
			$tolerance_level,
			$gst_name,
			$hsn_code,
			$active_text
		];
	}

	$fl_name = 'Product_Master.xlsx';
	\Shuchkin\SimpleXLSXGen::fromArray($excel_rows)->downloadAs($fl_name);
	exit();
}

 //Ruchi started
 if($_GET['sub'] == 'progrp'){
	include("../dbcon.php");
	require_once "../excel_libs/SimpleXLSXGen.php";

	$excel_rows = [];
	$excel_rows[] = ['Product Group List'];
	$excel_rows[] = [
		'Sr.No.',
		'Product Group'
	];

	$sql = "SELECT * FROM sma_product_group ORDER BY product_group ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while($row = mysqli_fetch_array($result)){
		$i++;
		$excel_rows[] = [
			$i,
			$row['product_group'] ?? ''
		];
	}

	$fl_name = 'Product_Group_List.xlsx';
	\Shuchkin\SimpleXLSXGen::fromArray($excel_rows)->downloadAs($fl_name);
	exit();
 }
 
 if($_GET['sub'] == 'unit'){
	include("../dbcon.php");
	require_once "../excel_libs/SimpleXLSXGen.php";

	$excel_rows = [];
	$excel_rows[] = ['Units List'];
	$excel_rows[] = [
		'Sr.No.',
		'Units'
	];

	$sql = "SELECT * FROM sma_units ORDER BY name ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while($row = mysqli_fetch_array($result)){
		$i++;
		$excel_rows[] = [
			$i,
			$row['name'] ?? ''
		];
	}

	$fl_name = 'Units_List.xlsx';
	\Shuchkin\SimpleXLSXGen::fromArray($excel_rows)->downloadAs($fl_name);
	exit();
 }
 ?>
