<?php
session_start();
ini_set('max_execution_time', 0);

if($_GET['sub'] == 'list'){
	include("../dbcon.php");
	require_once "../excel_libs/SimpleXLSXGen.php";

	$excel_rows = [];
	$excel_rows[] = ['Supplier Master'];
	$excel_rows[] = [
		'Sr.No.',
		'Registered By',
		'Supplier Type',
		'Supplier Name',
		'Type',
		'Category',
		'Contact Person',
		'Address',
		'City',
		'State',
		'Pincode',
		'Area',
		'Country',
		'Phone-1',
		'Phone-2',
		'Phone-3',
		'Mobile-1',
		'Mobile-2',
		'Mobile-3',
		'Email Id',
		'PAN No.',
		'GST No.',
		'MSME No.',
		'Name in A/c Software',
		'Bank Name',
		'Bank Account Type',
		'Beneficiary Name',
		'Bank Address',
		'Account No.',
		'IFSC Code',
		'KYC',
		'Created Date'
	];

	// Pre-fetch maps
	$comp_map = [];
	$res_c = mysqli_query($con, "SELECT comp_id, comp_name FROM company");
	while ($rc = mysqli_fetch_array($res_c)) {
		$comp_map[$rc['comp_id']] = $rc['comp_name'];
	}

	$type_map = [];
	$res_t = mysqli_query($con, "SELECT id, type FROM sma_type");
	while ($rt = mysqli_fetch_array($res_t)) {
		$type_map[$rt['id']] = $rt['type'];
	}

	$cat_map = [];
	$res_cat = mysqli_query($con, "SELECT id, name FROM sma_categories");
	while ($rcat = mysqli_fetch_array($res_cat)) {
		$cat_map[$rcat['id']] = $rcat['name'];
	}

	$city_map = [];
	$res_ct = mysqli_query($con, "SELECT id, city_name FROM cities");
	while ($rct = mysqli_fetch_array($res_ct)) {
		$city_map[$rct['id']] = $rct['city_name'];
	}

	$state_map = [];
	$res_st = mysqli_query($con, "SELECT id, state_name FROM states");
	while ($rst = mysqli_fetch_array($res_st)) {
		$state_map[$rst['id']] = $rst['state_name'];
	}

	$search = $_SESSION['search'] ?? ($_POST['search'] ?? '');

	$sql = "SELECT * FROM `sma_party_mst` WHERE 1";
	if (!empty($search)) {
		$sql .= " AND (party_name LIKE '%$search%' OR party_gst_number LIKE '%$search%' OR party_pan_number LIKE '%$search%' OR party_email LIKE '%$search%')";
	}
	$sql .= " ORDER BY party_name ASC";

	$result = mysqli_query($con, $sql);
	$i = 0;
	while($row = mysqli_fetch_array($result)){
		$i++;
		$comp_name     = $comp_map[$row['company_id']] ?? '';
		$party_type    = $type_map[$row['party_type']] ?? '';
		$category_name = $cat_map[$row['party_category']] ?? '';
		$city_name     = $city_map[$row['party_city']] ?? $row['party_city'];
		$state_name    = $state_map[$row['party_state']] ?? $row['party_state'];

		$created_dt = '';
		if (!empty($row['created_dated']) && $row['created_dated'] != '1970-01-01' && $row['created_dated'] != '0000-00-00') {
			$created_dt = date('d-m-Y', strtotime($row['created_dated']));
		}

		$excel_rows[] = [
			$i,
			$comp_name,
			$row['party_nature_business'] ?? '',
			$row['party_name'] ?? '',
			$party_type,
			$category_name,
			$row['party_contact_person_name'] ?? '',
			$row['party_address_1'] ?? '',
			$city_name,
			$state_name,
			$row['party_pincode'] ?? '',
			$row['party_area'] ?? '',
			$row['party_country'] ?? '',
			$row['party_phone'] ?? '',
			$row['party_phone1'] ?? '',
			$row['party_phone2'] ?? '',
			$row['party_mobile'] ?? '',
			$row['party_mobile1'] ?? '',
			$row['party_mobile2'] ?? '',
			$row['party_email'] ?? '',
			$row['party_pan_number'] ?? '',
			$row['party_gst_number'] ?? '',
			$row['party_msme_number'] ?? '',
			$row['tally_account_name'] ?? '',
			$row['party_bank_name'] ?? '',
			$row['party_bank_account_type'] ?? '',
			$row['party_beneficiary_name'] ?? '',
			$row['party_bank_address'] ?? '',
			$row['party_bank_account_no'] ?? '',
			$row['party_bank_ifsc_code'] ?? '',
			$row['party_kyc'] ?? '',
			$created_dt
		];
	}

	$fl_name = 'Supplier_Master.xlsx';
	\Shuchkin\SimpleXLSXGen::fromArray($excel_rows)->downloadAs($fl_name);
	exit();
}

if($_GET['sub'] == 'cat'){
	include("../dbcon.php");
	require_once "../excel_libs/SimpleXLSXGen.php";

	$excel_rows = [];
	$excel_rows[] = ['Supplier Category List'];
	$excel_rows[] = [
		'Sr.No.',
		'Category'
	];

	$sql = "SELECT * FROM sma_categories ORDER BY name ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while($row = mysqli_fetch_array($result)){
		$i++;
		$excel_rows[] = [
			$i,
			$row['name'] ?? ''
		];
	}

	$fl_name = 'Supplier_Category_List.xlsx';
	\Shuchkin\SimpleXLSXGen::fromArray($excel_rows)->downloadAs($fl_name);
	exit();
}

if($_GET['sub'] == 'Type'){
	include("../dbcon.php");
	require_once "../excel_libs/SimpleXLSXGen.php";

	$excel_rows = [];
	$excel_rows[] = ['Supplier Type List'];
	$excel_rows[] = [
		'Sr.No.',
		'Type'
	];

	$sql = "SELECT * FROM sma_type ORDER BY type ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while($row = mysqli_fetch_array($result)){
		$i++;
		$excel_rows[] = [
			$i,
			$row['type'] ?? ''
		];
	}

	$fl_name = 'Supplier_Type_List.xlsx';
	\Shuchkin\SimpleXLSXGen::fromArray($excel_rows)->downloadAs($fl_name);
	exit();
}

if($_GET['sub'] == 'states'){
	include("../dbcon.php");
	require_once "../excel_libs/SimpleXLSXGen.php";

	$excel_rows = [];
	$excel_rows[] = ['States List'];
	$excel_rows[] = [
		'Sr.No.',
		'States'
	];

	$sql = "SELECT * FROM states ORDER BY state_name ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while($row = mysqli_fetch_array($result)){
		$i++;
		$excel_rows[] = [
			$i,
			$row['state_name'] ?? ''
		];
	}

	$fl_name = 'states_list.xlsx';
	\Shuchkin\SimpleXLSXGen::fromArray($excel_rows)->downloadAs($fl_name);
	exit();
}

if($_GET['sub'] == 'cities'){
	include("../dbcon.php");
	require_once "../excel_libs/SimpleXLSXGen.php";

	$excel_rows = [];
	$excel_rows[] = ['Cities List'];
	$excel_rows[] = [
		'Sr.No.',
		'States',
		'Cities'
	];

	$sql = "SELECT b.state_name, a.city_name FROM cities a INNER JOIN states b ON b.id = a.states_id ORDER BY b.state_name ASC, a.city_name ASC";
	$result = mysqli_query($con, $sql);
	$i = 0;
	while($row = mysqli_fetch_array($result)){
		$i++;
		$excel_rows[] = [
			$i,
			$row['state_name'] ?? '',
			$row['city_name'] ?? ''
		];
	}

	$fl_name = 'cities_list.xlsx';
	\Shuchkin\SimpleXLSXGen::fromArray($excel_rows)->downloadAs($fl_name);
	exit();
}
?>
