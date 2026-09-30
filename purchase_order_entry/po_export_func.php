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
	$supplier_id   = $_POST['supplier_id'] ?? '';
	$company_id    = $_POST['company_id'] ?? '';

	$from_date = (!empty($from_date_raw) && $from_date_raw != '1970-01-01' && $from_date_raw != '0000-00-00') ? date('Y-m-d', strtotime($from_date_raw)) : '';
	$to_date   = (!empty($to_date_raw) && $to_date_raw != '1970-01-01' && $to_date_raw != '0000-00-00') ? date('Y-m-d', strtotime($to_date_raw)) : '';

	$from_date_dmy = (!empty($from_date_raw) && $from_date_raw != '1970-01-01' && $from_date_raw != '0000-00-00') ? date('d-m-Y', strtotime($from_date_raw)) : '';
	$to_date_dmy   = (!empty($to_date_raw) && $to_date_raw != '1970-01-01' && $to_date_raw != '0000-00-00') ? date('d-m-Y', strtotime($to_date_raw)) : '';

	if (!empty($from_date_dmy) && !empty($to_date_dmy)) {
		$title_banner = 'Open Purchase Order Detail Register from ' . $from_date_dmy . ' TO ' . $to_date_dmy;
	} else if (!empty($from_date_dmy)) {
		$title_banner = 'Open Purchase Order Detail Register from ' . $from_date_dmy;
	} else if (!empty($to_date_dmy)) {
		$title_banner = 'Open Purchase Order Detail Register up to ' . $to_date_dmy;
	} else {
		$title_banner = 'Open Purchase Order Detail Register';
	}

	$excel_rows = [];
	$excel_rows[] = [$title_banner];
	$excel_rows[] = [
		'Sr.No.',
		'Company',
		'Location',
		'Department',
		'Status',
		'PO Number',
		'Dated',
		'Supplier',
		'NOA No.',
		'Supp. Quote Ref. No.',
		'Delivery Days',
		'Credit Days',
		'Discount',
		'Transport',
		'Other Charges',
		'Item Sr.No.',
		'Material',
		'Description',
		'Unit',
		'Qty.',
		'Rate',
		'Total Amt.',
		'GST%',
		'Net Amt.',
		'Budget Group',
		'Budget Sub Group',
		'Budget Code',
		'Supp. Inv. No.',
		'Inv. Date',
		'Inv. Qty.',
		'Inv. GST',
		'Inv. Amount'
	];

	if (!empty($_SESSION['sqlex'])) {
		$sql = $_SESSION['sqlex'];
	} else {
		$comid = $_SESSION['comid'] ?? '';
		$sql = "SELECT * FROM sma_purchase_order WHERE del != 'Y' AND approval_memo_ref = 'Open PO'";
		if (!empty($from_date) && !empty($to_date)) {
			$sql .= " AND dated >= '$from_date' AND dated <= '$to_date'";
		}
		if (!empty($company_id)) {
			$sql .= " AND project = '$company_id'";
		}
		if (!empty($department_id)) {
			$sql .= " AND department = '$department_id'";
		}
		if (!empty($supplier_id)) {
			$sql .= " AND to_supplier = '$supplier_id'";
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
		$pur_id                 = $row['id'] ?? '';
		$po_number              = $row['po_number'] ?? '';
		$dated_raw              = $row['dated'] ?? '';
		$to_supplier            = $row['to_supplier'] ?? '';
		$company_id_row         = $row['project'] ?? '';
		$department_id_row      = $row['department'] ?? '';
		$location_id_row        = $row['location'] ?? '';
		$status                 = $row['status'] ?? '';
		$approval_memo_ref_raw  = $row['approval_memo_ref'] ?? '';
		$quotation_reference_no = $row['quotation_reference_no'] ?? '';
		$delivery_days          = $row['delivery_days'] ?? '';
		$credit_days            = $row['credit_days'] ?? '';
		$discount               = $row['discount'] ?? '';
		$transport              = $row['transport'] ?? '';
		$other_charges          = $row['other_charges'] ?? '';
		$hdr_budget_name        = $row['budget_name'] ?? '';
		$hdr_budget_head        = $row['budget_head'] ?? '';

		$po_dated = (!empty($dated_raw) && $dated_raw != '1970-01-01' && $dated_raw != '0000-00-00') ? date('d-m-Y', strtotime($dated_raw)) : '';

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
		$supplier_name = '';
		if (!empty($to_supplier)) {
			$sql_sup = "SELECT party_name FROM `sma_party_mst` WHERE id = '$to_supplier'";
			$res_sup = mysqli_query($con, $sql_sup);
			if ($res_sup && $r_sup = mysqli_fetch_array($res_sup)) {
				$supplier_name = $r_sup['party_name'] ?? '';
			}
		}

		// Memo ref resolution
		$approval_memo_ref = $approval_memo_ref_raw;
		if (!empty($approval_memo_ref_raw) && is_numeric($approval_memo_ref_raw)) {
			$sql_memo = "SELECT dated FROM `sma_approval_memo` WHERE id = '$approval_memo_ref_raw'";
			$res_memo = mysqli_query($con, $sql_memo);
			if ($res_memo && $r_memo = mysqli_fetch_array($res_memo)) {
				$memo_date = (!empty($r_memo['dated']) && $r_memo['dated'] != '1970-01-01' && $r_memo['dated'] != '0000-00-00') ? date('d-m-Y', strtotime($r_memo['dated'])) : '';
				if (!empty($memo_date)) {
					$approval_memo_ref = $approval_memo_ref_raw . ' - ' . $memo_date;
				}
			}
		}

		// Items query
		$sql_items = "SELECT * FROM `sma_po_items` WHERE purchase_id = '$pur_id'";
		$res_items = mysqli_query($con, $sql_items);
		$has_items = false;

		while ($r_item = mysqli_fetch_array($res_items)) {
			$has_items = true;
			$po_item_id   = $r_item['id'] ?? '';
			$product_id   = $r_item['product_id'] ?? '';
			$material     = $r_item['product_name'] ?? '';
			$description  = $r_item['product_desc'] ?? '';
			$unit         = $r_item['uom'] ?? '';
			$quantity     = floatval($r_item['quantity'] ?? 0);
			$unit_rate    = floatval($r_item['unit_rate'] ?? 0);
			$gst          = floatval($r_item['gst'] ?? 0);
			$item_b_id    = $r_item['budget_id'] ?? 0;

			$total_amount = round($quantity * $unit_rate, 2);
			$net_amount   = round($total_amount + ($total_amount * $gst / 100), 2);

			// Product name fallback
			$prod_b_head = '';
			$prod_b_name = '';
			if (!empty($product_id)) {
				$sql_mat = "SELECT name, uom, budget_head, budget_name FROM `sma_product` WHERE id = '$product_id'";
				$res_mat = mysqli_query($con, $sql_mat);
				if ($res_mat && $r_mat = mysqli_fetch_array($res_mat)) {
					if (empty($material)) {
						$material = $r_mat['name'] ?? '';
					}
					if (empty($unit)) {
						$unit = $r_mat['uom'] ?? '';
					}
					$prod_b_head = $r_mat['budget_head'] ?? '';
					$prod_b_name = $r_mat['budget_name'] ?? '';
				}
			}

			// Budget Group & Budget Sub Group (Comprehensive Multi-Tier Fallback)
			$budget_name = '';
			$budget_head = '';
			$budget_code = '';

			// 1. From sma_budget via item_b_id
			if (!empty($item_b_id)) {
				$sql_b = "SELECT a.budget_head, a.budget_code, b.name AS budget_name 
				          FROM `sma_budget` a 
				          LEFT JOIN `sma_budget_name` b ON a.budget_name = b.id 
				          WHERE a.id = '$item_b_id'";
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

			// 2. From Product master
			if ((empty($budget_name) || empty($budget_head)) && !empty($prod_b_head)) {
				$sql_sub = "SELECT budget_head, budget_name FROM `sma_budget_subgroup` WHERE id = '$prod_b_head'";
				$res_sub = mysqli_query($con, $sql_sub);
				if ($res_sub && $r_sub = mysqli_fetch_array($res_sub)) {
					$budget_head = $r_sub['budget_head'] ?? '';
					if (empty($prod_b_name) && !empty($r_sub['budget_name'])) {
						$prod_b_name = $r_sub['budget_name'];
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

			// 3. Fallback from Header
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

			// Invoice details query for this PO item
			$supp_inv_no  = '';
			$inv_date     = '';
			$inv_qty      = '';
			$inv_gst      = '';
			$inv_amt      = '';

			if (!empty($po_item_id)) {
				$sql_inv = "SELECT a.qty, a.gst, a.amount, b.supplier_invoice_no, b.invoice_date 
				            FROM `sma_supplier_invoice_details` a 
				            LEFT JOIN `sma_supplier_invoice` b ON a.si_hdr_id = b.id 
				            WHERE a.po_item_id = '$po_item_id' AND a.our_po_ref_no = '$pur_id' 
				            LIMIT 1";
				$res_inv = mysqli_query($con, $sql_inv);
				if ($res_inv && $r_inv = mysqli_fetch_array($res_inv)) {
					$supp_inv_no = $r_inv['supplier_invoice_no'] ?? '';
					if (!empty($r_inv['invoice_date']) && $r_inv['invoice_date'] != '1970-01-01' && $r_inv['invoice_date'] != '0000-00-00') {
						$inv_date = date('d-m-Y', strtotime($r_inv['invoice_date']));
					}
					$inv_qty = $r_inv['qty'] ?? '';
					$inv_gst = $r_inv['gst'] ?? '';
					$inv_amt = $r_inv['amount'] ?? '';
				}
			}

			$excel_rows[] = [
				$ln,
				$comp_name,
				$location_name,
				$dept_name,
				$status,
				$po_number,
				$po_dated,
				$supplier_name,
				$approval_memo_ref,
				$quotation_reference_no,
				$delivery_days,
				$credit_days,
				$discount,
				$transport,
				$other_charges,
				$po_item_id,
				$material,
				$description,
				$unit,
				$quantity,
				$unit_rate,
				$total_amount,
				$gst,
				$net_amount,
				$budget_name,
				$budget_head,
				$budget_code,
				$supp_inv_no,
				$inv_date,
				$inv_qty,
				$inv_gst,
				$inv_amt
			];
		}

		if (!$has_items) {
			$excel_rows[] = [
				$ln,
				$comp_name,
				$location_name,
				$dept_name,
				$status,
				$po_number,
				$po_dated,
				$supplier_name,
				$approval_memo_ref,
				$quotation_reference_no,
				$delivery_days,
				$credit_days,
				$discount,
				$transport,
				$other_charges,
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				''
			];
		}
	}

	if ($prn == 'excel') {
		$fl_name = 'Open_PO_Detail_Report_' . date('Y-m-d') . '.xlsx';
		\Shuchkin\SimpleXLSXGen::fromArray($excel_rows)->downloadAs($fl_name);
		exit();
	}
}
?>