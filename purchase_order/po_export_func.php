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
		$title_banner = 'PO Details Report from ' . $from_date_dmy . ' TO ' . $to_date_dmy;
	} else if (!empty($from_date_dmy)) {
		$title_banner = 'PO Details Report from ' . $from_date_dmy;
	} else if (!empty($to_date_dmy)) {
		$title_banner = 'PO Details Report up to ' . $to_date_dmy;
	} else {
		$title_banner = 'PO Details Report';
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
		'Item Sr.No.',
		'Budget Group',
		'Budget Sub Group',
		'Material',
		'Unit',
		'Qty.',
		'Rate',
		'Total Amt.',
		'GST%',
		'Net Amt.',
		'Invoice No.',
		'Inv. Date',
		'Inv. Qty.',
		'Inv. GST',
		'Inv. Amount'
	];

	if (!empty($_SESSION['sqlex'])) {
		$sql = $_SESSION['sqlex'];
	} else {
		$comid = $_SESSION['comid'] ?? '';
		$sql = "SELECT * FROM sma_purchase_order WHERE del != 'Y'";
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
		$approval_memo_ref      = $row['approval_memo_ref'] ?? '';
		$quotation_reference_no = $row['quotation_reference_no'] ?? '';
		$delivery_days          = $row['delivery_days'] ?? '';
		$credit_days            = $row['credit_days'] ?? '';
		$discount               = $row['discount'] ?? '';
		$transport              = $row['transport'] ?? '';
		$other_charges          = $row['other_charges'] ?? '';

		$po_dated = (!empty($dated_raw) && $dated_raw != '1970-01-01' && $dated_raw != '0000-00-00') ? date('d-m-Y', strtotime($dated_raw)) : '';

		// NOA reference date lookup
		$noa_display = $approval_memo_ref;
		$noa_indent_id = '';
		$noa_budget_head_id = '';
		if (!empty($approval_memo_ref)) {
			$sql_noa = "SELECT id, dated, against_indent_no, budget_head FROM `sma_approval_memo` WHERE id = '$approval_memo_ref'";
			$res_noa = mysqli_query($con, $sql_noa);
			if ($res_noa && $rw_noa = mysqli_fetch_array($res_noa)) {
				$noa_date = (!empty($rw_noa['dated']) && $rw_noa['dated'] != '1970-01-01' && $rw_noa['dated'] != '0000-00-00') ? date('d-m-Y', strtotime($rw_noa['dated'])) : '';
				if (!empty($noa_date)) {
					$noa_display = $approval_memo_ref . ' - ' . $noa_date;
				}
				$noa_indent_id = $rw_noa['against_indent_no'] ?? '';
				$noa_budget_head_id = $rw_noa['budget_head'] ?? '';
			}
		}

		// Location lookup
		$loc_name = '';
		if (!empty($location_id_row)) {
			$sql_l = "SELECT loc_name FROM `sma_location` WHERE id = '$location_id_row'";
			$res_l = mysqli_query($con, $sql_l);
			if ($res_l && $lc = mysqli_fetch_array($res_l)) {
				$loc_name = $lc['loc_name'] ?? '';
			}
		}

		// Supplier lookup
		$party_name = '';
		if (!empty($to_supplier)) {
			$sql_p = "SELECT party_name FROM `sma_party_mst` WHERE id = '$to_supplier'";
			$res_p = mysqli_query($con, $sql_p);
			if ($res_p && $rw_p = mysqli_fetch_array($res_p)) {
				$party_name = $rw_p['party_name'] ?? '';
			}
		}

		// Company lookup
		$comp_name = '';
		if (!empty($company_id_row)) {
			$sql_c = "SELECT comp_code, comp_name FROM `company` WHERE comp_id = '$company_id_row'";
			$res_c = mysqli_query($con, $sql_c);
			if ($res_c && $com = mysqli_fetch_array($res_c)) {
				$comp_name = !empty($com['comp_code']) ? $com['comp_code'] : ($com['comp_name'] ?? '');
			}
		}

		// Department lookup
		$department_name = '';
		if (!empty($department_id_row)) {
			$sql_d = "SELECT name FROM `sma_department` WHERE id = '$department_id_row'";
			$res_d = mysqli_query($con, $sql_d);
			if ($res_d && $deps = mysqli_fetch_array($res_d)) {
				$department_name = $deps['name'] ?? '';
			}
		}

		// Fetch PO Items
		$sql_items = "SELECT * FROM `sma_po_items` WHERE purchase_id = '$pur_id'";
		$res_items = mysqli_query($con, $sql_items);
		$has_items = false;
		$item_index = 0;

		while ($rw = mysqli_fetch_array($res_items)) {
			$has_items = true;
			$item_index++;
			$b_id         = $rw['budget_id'] ?? 0;
			$prod_id      = $rw['product_id'] ?? 0;
			$quantity     = floatval($rw['quantity'] ?? 0);
			$unit_rate    = floatval($rw['unit_rate'] ?? 0);
			$gst          = floatval($rw['gst'] ?? 0);
			$product_desc = $rw['product_desc'] ?? '';

			$actual_amt = round($quantity * $unit_rate, 2);
			$net_amt    = round($actual_amt + ($actual_amt * $gst / 100), 2);

			// Product name & unit lookup
			$product_name = '';
			$unit = '';
			if (!empty($prod_id)) {
				$sql_prod = "SELECT name, uom, budget_head, budget_name FROM `sma_product` WHERE id = '$prod_id'";
				$res_prod = mysqli_query($con, $sql_prod);
				if ($res_prod && $r_prod = mysqli_fetch_array($res_prod)) {
					$product_name = $r_prod['name'] ?? '';
					$unit         = $r_prod['uom'] ?? '';
					$prod_b_head  = $r_prod['budget_head'] ?? '';
					$prod_b_name  = $r_prod['budget_name'] ?? '';
				}
			}

			// Budget Group & Sub Group & Code Lookup
			$budget_name = '';
			$budget_head = '';
			$budget_code = '';

			if (!empty($b_id)) {
				$sql_b = "SELECT a.budget_head, a.budget_code, b.name AS budget_name 
				          FROM `sma_budget` a 
				          LEFT JOIN `sma_budget_name` b ON a.budget_name = b.id 
				          WHERE a.id = '$b_id'";
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

			if ((empty($budget_name) || empty($budget_head)) && !empty($prod_b_head)) {
				$sql_sub = "SELECT budget_head, budget_name FROM `sma_budget_subgroup` WHERE id = '$prod_b_head'";
				$res_sub = mysqli_query($con, $sql_sub);
				if ($res_sub && $r_sub = mysqli_fetch_array($res_sub)) {
					$budget_head = $r_sub['budget_head'] ?? '';
					if (empty($budget_name) && !empty($r_sub['budget_name'])) {
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

			// Fallback from linked PR / NOA
			if ((empty($budget_name) || empty($budget_head)) && !empty($noa_indent_id)) {
				$sql_pri = "SELECT p.budget_head, p.budget_name 
				            FROM `sma_purchase_req_items` pri 
				            LEFT JOIN `sma_product` p ON pri.product_id = p.id 
				            WHERE pri.purchase_req_id = '$noa_indent_id' AND (p.budget_head > 0 OR p.budget_name > 0) 
				            LIMIT 1";
				$res_pri = mysqli_query($con, $sql_pri);
				if ($res_pri && $r_pri = mysqli_fetch_array($res_pri)) {
					$p_head = $r_pri['budget_head'] ?? '';
					$p_name = $r_pri['budget_name'] ?? '';

					if (empty($budget_head) && !empty($p_head)) {
						$sql_sub = "SELECT budget_head, budget_name FROM `sma_budget_subgroup` WHERE id = '$p_head'";
						$res_sub = mysqli_query($con, $sql_sub);
						if ($res_sub && $r_sub = mysqli_fetch_array($res_sub)) {
							$budget_head = $r_sub['budget_head'] ?? '';
							if (empty($p_name) && !empty($r_sub['budget_name'])) {
								$p_name = $r_sub['budget_name'];
							}
						}
					}
					if (empty($budget_name) && !empty($p_name)) {
						$sql_bn = "SELECT name FROM `sma_budget_name` WHERE id = '$p_name'";
						$res_bn = mysqli_query($con, $sql_bn);
						if ($res_bn && $r_bn = mysqli_fetch_array($res_bn)) {
							$budget_name = $r_bn['name'] ?? '';
						}
					}
				}
			}

			// Fetch linked Supplier Invoices for this PO Item
			$poi_id = $rw['id'] ?? 0;
			$sql_inv_match = "";
			if (!empty($poi_id) && !empty($prod_id)) {
				$sql_inv_match = "(sid.po_item_id = '$poi_id' OR sid.material_id = '$prod_id')";
			} else if (!empty($poi_id)) {
				$sql_inv_match = "sid.po_item_id = '$poi_id'";
			} else if (!empty($prod_id)) {
				$sql_inv_match = "sid.material_id = '$prod_id'";
			}

			$item_invoices = [];
			if (!empty($sql_inv_match)) {
				$sql_inv = "SELECT si.supplier_invoice_no, si.invoice_date, sid.qty, sid.gst, sid.amount 
				            FROM `sma_supplier_invoice` si 
				            JOIN `sma_supplier_invoice_details` sid ON si.id = sid.si_hdr_id 
				            WHERE (si.our_po_ref_no = '$pur_id' OR si.our_po_ref_no = '$po_number') 
				              AND si.del != 'Y' 
				              AND si.status != 'Draft'
				              AND $sql_inv_match 
				              AND sid.qty > 0";
				$res_inv = mysqli_query($con, $sql_inv);
				while ($res_inv && $r_inv = mysqli_fetch_array($res_inv)) {
					$inv_d = (!empty($r_inv['invoice_date']) && $r_inv['invoice_date'] != '1970-01-01' && $r_inv['invoice_date'] != '0000-00-00') ? date('d-m-Y', strtotime($r_inv['invoice_date'])) : '';
					$item_invoices[] = [
						'invoice_no' => $r_inv['supplier_invoice_no'] ?? '',
						'inv_date'   => $inv_d,
						'inv_qty'    => isset($r_inv['qty']) ? floatval($r_inv['qty']) : '',
						'inv_gst'    => isset($r_inv['gst']) ? floatval($r_inv['gst']) : '',
						'inv_amount' => isset($r_inv['amount']) ? floatval($r_inv['amount']) : ''
					];
				}
			}

			if (!empty($item_invoices)) {
				foreach ($item_invoices as $inv_row) {
					$excel_rows[] = [
						$ln,
						$comp_name,
						$loc_name,
						$department_name,
						$status,
						$po_number,
						$po_dated,
						$party_name,
						$noa_display,
						$quotation_reference_no,
						$item_index,
						$budget_name,
						$budget_head,
						$product_name,
						$unit,
						$quantity,
						$unit_rate,
						$actual_amt,
						$gst,
						$net_amt,
						$inv_row['invoice_no'],
						$inv_row['inv_date'],
						$inv_row['inv_qty'],
						$inv_row['inv_gst'],
						$inv_row['inv_amount']
					];
				}
			} else {
				$excel_rows[] = [
					$ln,
					$comp_name,
					$loc_name,
					$department_name,
					$status,
					$po_number,
					$po_dated,
					$party_name,
					$noa_display,
					$quotation_reference_no,
					$item_index,
					$budget_name,
					$budget_head,
					$product_name,
					$unit,
					$quantity,
					$unit_rate,
					$actual_amt,
					$gst,
					$net_amt,
					'',
					'',
					'',
					'',
					''
				];
			}
		}

		if (!$has_items) {
			$po_invoices = [];
			$sql_inv = "SELECT si.supplier_invoice_no, si.invoice_date, sid.qty, sid.gst, sid.amount 
			            FROM `sma_supplier_invoice` si 
			            LEFT JOIN `sma_supplier_invoice_details` sid ON si.id = sid.si_hdr_id 
			            WHERE (si.our_po_ref_no = '$pur_id' OR si.our_po_ref_no = '$po_number') 
			              AND si.del != 'Y' 
			              AND si.status != 'Draft'";
			$res_inv = mysqli_query($con, $sql_inv);
			while ($res_inv && $r_inv = mysqli_fetch_array($res_inv)) {
				$inv_d = (!empty($r_inv['invoice_date']) && $r_inv['invoice_date'] != '1970-01-01' && $r_inv['invoice_date'] != '0000-00-00') ? date('d-m-Y', strtotime($r_inv['invoice_date'])) : '';
				$po_invoices[] = [
					'invoice_no' => $r_inv['supplier_invoice_no'] ?? '',
					'inv_date'   => $inv_d,
					'inv_qty'    => (isset($r_inv['qty']) && floatval($r_inv['qty']) > 0) ? floatval($r_inv['qty']) : '',
					'inv_gst'    => (isset($r_inv['gst']) && floatval($r_inv['gst']) > 0) ? floatval($r_inv['gst']) : '',
					'inv_amount' => (isset($r_inv['amount']) && floatval($r_inv['amount']) > 0) ? floatval($r_inv['amount']) : ''
				];
			}

			if (!empty($po_invoices)) {
				foreach ($po_invoices as $inv_row) {
					$excel_rows[] = [
						$ln,
						$comp_name,
						$loc_name,
						$department_name,
						$status,
						$po_number,
						$po_dated,
						$party_name,
						$noa_display,
						$quotation_reference_no,
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
						$inv_row['invoice_no'],
						$inv_row['inv_date'],
						$inv_row['inv_qty'],
						$inv_row['inv_gst'],
						$inv_row['inv_amount']
					];
				}
			} else {
				$excel_rows[] = [
					$ln,
					$comp_name,
					$loc_name,
					$department_name,
					$status,
					$po_number,
					$po_dated,
					$party_name,
					$noa_display,
					$quotation_reference_no,
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
	}

	if ($prn == 'excel') {
		$fl_name = 'PO_Detail_Report_' . date('Y-m-d') . '.xlsx';
		\Shuchkin\SimpleXLSXGen::fromArray($excel_rows)->downloadAs($fl_name);
		exit();
	}
}
?>