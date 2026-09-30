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
		$title_banner = 'Open Purchase Order Summary Report from ' . $from_date_dmy . ' TO ' . $to_date_dmy;
	} else if (!empty($from_date_dmy)) {
		$title_banner = 'Open Purchase Order Summary Report from ' . $from_date_dmy;
	} else if (!empty($to_date_dmy)) {
		$title_banner = 'Open Purchase Order Summary Report up to ' . $to_date_dmy;
	} else {
		$title_banner = 'Open Purchase Order Summary Report';
	}

	$excel_rows = [];
	$excel_rows[] = [$title_banner];
	$excel_rows[] = [
		'Sr.No.',
		'WO/PO Number',
		'SPV',
		'Department',
		'Location',
		'WO Date',
		'Budget Group',
		'Budget Sub Group',
		'Budget Code',
		'Supplier',
		'Supplier Address',
		'WO/PO For',
		'Total PO Amount',
		'Used PO Amount',
		'Bal. PO Amount',
		'Status'
	];

	if (!empty($_SESSION['sqlex'])) {
		$sql = $_SESSION['sqlex'];
		if (stripos($sql, 'approval_status') === false) {
			$sql .= " AND approval_status != 'Rejected'";
		}
	} else {
		$comid = $_SESSION['comid'] ?? '';
		$sql = "SELECT * FROM sma_purchase_order WHERE del != 'Y' AND approval_memo_ref = 'Open PO' AND approval_status != 'Rejected'";
		if (!empty($comid)) {
			$sql .= " AND project IN ($comid)";
		}
		if (!empty($from_date) && !empty($to_date)) {
			$sql .= " AND dated >= '$from_date' AND dated <= '$to_date'";
		}
		if (!empty($company_id)) {
			$sql .= " AND project = '$company_id'";
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
		$pur_id             = $row['id'] ?? '';
		$po_number          = $row['po_number'] ?? '';
		$dated_raw          = $row['dated'] ?? '';
		$to_supplier        = $row['to_supplier'] ?? '';
		$company_id_row     = $row['project'] ?? '';
		$department_id_row  = $row['department'] ?? '';
		$location_id_row    = $row['location'] ?? '';
		$status             = $row['status'] ?? '';
		$subject            = $row['subject'] ?? '';
		$approval_memo_ref  = $row['approval_memo_ref'] ?? '';
		$hdr_budget_name    = $row['budget_name'] ?? '';
		$hdr_budget_head    = $row['budget_head'] ?? '';

		$po_dated = (!empty($dated_raw) && $dated_raw != '1970-01-01' && $dated_raw != '0000-00-00') ? date('d-m-Y', strtotime($dated_raw)) : '';

		// Date from workflow history if available
		if (!empty($pur_id)) {
			$s1 = "SELECT create_date FROM `workflow_history` WHERE doc_id = '$pur_id' AND doc_type = 'PO' ORDER BY id DESC LIMIT 1";
			$q1 = mysqli_query($con, $s1);
			if ($q1 && $r1 = mysqli_fetch_array($q1)) {
				if (!empty($r1['create_date']) && $r1['create_date'] != '1970-01-01' && $r1['create_date'] != '0000-00-00') {
					$po_dated = date('d-m-Y', strtotime($r1['create_date']));
				}
			}
		}

		// Company lookup
		$comp_name = '';
		if (!empty($company_id_row)) {
			$sql_comp = "SELECT comp_name, comp_code FROM `company` WHERE comp_id = '$company_id_row'";
			$res_comp = mysqli_query($con, $sql_comp);
			if ($res_comp && $r_comp = mysqli_fetch_array($res_comp)) {
				$comp_name = !empty($r_comp['comp_code']) ? $r_comp['comp_code'] : ($r_comp['comp_name'] ?? '');
			}
		}

		// Department lookup
		$dept_name = '';
		if (!empty($department_id_row)) {
			$sql_dept = "SELECT name FROM `sma_departments` WHERE id = '$department_id_row'";
			$res_dept = mysqli_query($con, $sql_dept);
			if ($res_dept && $r_dept = mysqli_fetch_array($res_dept)) {
				$dept_name = $r_dept['name'] ?? '';
			}
		}

		// Location lookup
		$location_name = '';
		if (!empty($location_id_row)) {
			$sql_loc = "SELECT location FROM `sma_location_mst` WHERE id = '$location_id_row'";
			$res_loc = mysqli_query($con, $sql_loc);
			if ($res_loc && $r_loc = mysqli_fetch_array($res_loc)) {
				$location_name = $r_loc['location'] ?? '';
			}
		}

		// Supplier lookup
		$supplier_name    = '';
		$supplier_address = '';
		if (!empty($to_supplier)) {
			$sql_sup = "SELECT party_name, address FROM `sma_party_mst` WHERE id = '$to_supplier'";
			$res_sup = mysqli_query($con, $sql_sup);
			if ($res_sup && $r_sup = mysqli_fetch_array($res_sup)) {
				$supplier_name    = $r_sup['party_name'] ?? '';
				$supplier_address = $r_sup['address'] ?? '';
			}
		}

		// Total PO amount calculation
		$tot_po_amount = 0;
		$sql_tot = "SELECT SUM((quantity * unit_rate) + ((quantity * unit_rate) * gst / 100)) AS total_val FROM `sma_po_items` WHERE purchase_id = '$pur_id'";
		$res_tot = mysqli_query($con, $sql_tot);
		if ($res_tot && $r_tot = mysqli_fetch_array($res_tot)) {
			$tot_po_amount = floatval($r_tot['total_val'] ?? 0);
		}

		// Used PO amount calculation from supplier invoices
		$used_po_amount = 0;
		$sql_used = "SELECT SUM(amount) AS used_po_amount FROM `sma_supplier_invoice_details` WHERE our_po_ref_no = '$pur_id'";
		$res_used = mysqli_query($con, $sql_used);
		if ($res_used && $r_used = mysqli_fetch_array($res_used)) {
			$used_po_amount = floatval($r_used['used_po_amount'] ?? 0);
		}
		$bal_po_amount = $tot_po_amount - $used_po_amount;

		// Budget Group & Budget Sub Group (Comprehensive Multi-Tier Fallback)
		$budget_name = '';
		$budget_head = '';
		$budget_code = '';

		// 1. From sma_po_items (budget_id / product_id)
		$sql_poi = "SELECT budget_id, product_id FROM `sma_po_items` WHERE purchase_id = '$pur_id' AND (budget_id > 0 OR product_id > 0) ORDER BY (budget_id > 0) DESC LIMIT 1";
		$res_poi = mysqli_query($con, $sql_poi);
		if ($res_poi && $r_poi = mysqli_fetch_array($res_poi)) {
			$p_bid  = $r_poi['budget_id'] ?? 0;
			$p_prid = $r_poi['product_id'] ?? 0;

			if (!empty($p_bid)) {
				$sql_b = "SELECT a.budget_head, a.budget_code, b.name AS budget_name 
				          FROM `sma_budget` a 
				          LEFT JOIN `sma_budget_name` b ON a.budget_name = b.id 
				          WHERE a.id = '$p_bid'";
				$res_b = mysqli_query($con, $sql_b);
				if ($res_b && $r_b = mysqli_fetch_array($res_b)) {
					$budget_name = $r_b['budget_name'] ?? '';
					$b_sub_id    = $r_b['budget_head'] ?? '';
					$budget_code = $r_b['budget_code'] ?? '';

					if (!empty($b_sub_id)) {
						$sql_sub = "SELECT budget_head, budget_name FROM `sma_budget_subgroup` WHERE id = '$b_sub_id'";
						$res_sub = mysqli_query($con, $sql_sub);
						if ($res_sub && $r_sub = mysqli_fetch_array($res_sub)) {
							$budget_head = $r_sub['budget_head'] ?? '';
							if (empty($budget_name) && !empty($r_sub['budget_name'])) {
								$sub_bn_id = $r_sub['budget_name'];
								$sql_bn = "SELECT name FROM `sma_budget_name` WHERE id = '$sub_bn_id'";
								$res_bn = mysqli_query($con, $sql_bn);
								if ($res_bn && $r_bn = mysqli_fetch_array($res_bn)) {
									$budget_name = $r_bn['name'] ?? '';
								}
							}
						}
					}
				}
			}

			// 2. From Product master if budget still empty
			if ((empty($budget_name) || empty($budget_head)) && !empty($p_prid)) {
				$sql_mat = "SELECT budget_head, budget_name FROM `sma_product` WHERE id = '$p_prid'";
				$res_mat = mysqli_query($con, $sql_mat);
				if ($res_mat && $r_mat = mysqli_fetch_array($res_mat)) {
					$prod_b_head = $r_mat['budget_head'] ?? '';
					$prod_b_name = $r_mat['budget_name'] ?? '';

					if (!empty($prod_b_head)) {
						$sql_sub = "SELECT budget_head, budget_name FROM `sma_budget_subgroup` WHERE id = '$prod_b_head'";
						$res_sub = mysqli_query($con, $sql_sub);
						if ($res_sub && $r_sub = mysqli_fetch_array($res_sub)) {
							$budget_head = $r_sub['budget_head'] ?? '';
							if (empty($prod_b_name) && !empty($r_sub['budget_name'])) {
								$prod_b_name = $r_sub['budget_name'];
							}
						}
					}
					if (empty($budget_name) && !empty($prod_b_name)) {
						$sql_bn = "SELECT name FROM `sma_budget_name` WHERE id = '$prod_b_name'";
						$res_bn = mysqli_query($con, $sql_bn);
						if ($res_bn && $r_bn = mysqli_fetch_array($res_bn)) {
							$budget_name = $r_bn['name'] ?? '';
						}
					}
				}
			}
		}

		// 3. Fallback from Header budget fields if still empty
		if (empty($budget_name) && !empty($hdr_budget_name)) {
			$sql_bn = "SELECT name FROM `sma_budget_name` WHERE id = '$hdr_budget_name'";
			$res_bn = mysqli_query($con, $sql_bn);
			if ($res_bn && $r_bn = mysqli_fetch_array($res_bn)) {
				$budget_name = $r_bn['name'] ?? '';
			} else {
				$budget_name = $hdr_budget_name;
			}
		}
		if (empty($budget_head) && !empty($hdr_budget_head)) {
			$sql_sub = "SELECT budget_head FROM `sma_budget_subgroup` WHERE id = '$hdr_budget_head'";
			$res_sub = mysqli_query($con, $sql_sub);
			if ($res_sub && $r_sub = mysqli_fetch_array($res_sub)) {
				$budget_head = $r_sub['budget_head'] ?? '';
			} else {
				$budget_head = $hdr_budget_head;
			}
		}

		$excel_rows[] = [
			$ln,
			$po_number,
			$comp_name,
			$dept_name,
			$location_name,
			$po_dated,
			$budget_name,
			$budget_head,
			$budget_code,
			$supplier_name,
			$supplier_address,
			$subject,
			$tot_po_amount,
			$used_po_amount,
			$bal_po_amount,
			$status
		];
	}

	if ($prn == 'excel') {
		$fl_name = 'Open_PO_Summary_Report_' . date('Y-m-d') . '.xlsx';
		\Shuchkin\SimpleXLSXGen::fromArray($excel_rows)->downloadAs($fl_name);
		exit();
	}
}
?>