<?php
session_start();

if(isset($_GET['sub']) && $_GET['sub'] == 'pdf'){
	include "../dbcon.php";
	include "../baseurl.php";
	require_once "../excel_libs/SimpleXLSXGen.php";

	$prn = "excel";
	$from_date_raw = $_POST['from_date'] ?? $_SESSION['start_date'] ?? '';
	$to_date_raw   = $_POST['to_date'] ?? $_SESSION['end_date'] ?? '';
	$supplier_id   = $_POST['supplier_id'] ?? '';
	$company_id    = $_POST['company_id'] ?? '';

	$from_date = (!empty($from_date_raw) && $from_date_raw != '1970-01-01' && $from_date_raw != '0000-00-00') ? date('Y-m-d', strtotime($from_date_raw)) : '';
	$to_date   = (!empty($to_date_raw) && $to_date_raw != '1970-01-01' && $to_date_raw != '0000-00-00') ? date('Y-m-d', strtotime($to_date_raw)) : '';

	$from_date_dmy = (!empty($from_date_raw) && $from_date_raw != '1970-01-01' && $from_date_raw != '0000-00-00') ? date('d-m-Y', strtotime($from_date_raw)) : '';
	$to_date_dmy   = (!empty($to_date_raw) && $to_date_raw != '1970-01-01' && $to_date_raw != '0000-00-00') ? date('d-m-Y', strtotime($to_date_raw)) : '';

	if (!empty($from_date_dmy) && !empty($to_date_dmy)) {
		$title_banner = 'Supplier Invoice Summary Register from ' . $from_date_dmy . ' TO ' . $to_date_dmy;
	} else if (!empty($from_date_dmy)) {
		$title_banner = 'Supplier Invoice Summary Register from ' . $from_date_dmy;
	} else if (!empty($to_date_dmy)) {
		$title_banner = 'Supplier Invoice Summary Register up to ' . $to_date_dmy;
	} else {
		$title_banner = 'Supplier Invoice Summary Register';
	}

	$excel_rows = [];
	$excel_rows[] = [$title_banner];
	$excel_rows[] = [
		'Sr.No.',
		'SI.No.',
		'Supplier Name',
		'PAN',
		'GST',
		'MSME',
		'Supplier Inv.No.',
		'Invoice Date',
		'PO.Ref.No.',
		'Credit Days',
		'Due Date',
		'GRN No.',
		'Material Name',
		'Description',
		'Account Year',
		'Company',
		'Budget Group',
		'Budget Sub Group',
		'Budget Code',
		'Unit',
		'Qty.',
		'Rate',
		'GST%',
		'Total',
		'Approved By',
		'Workflow Type',
		'Status'
	];

	if (!empty($_SESSION['sqlex'])) {
		$sql = $_SESSION['sqlex'];
	} else {
		$comid = $_SESSION['comid'] ?? '';
		$sql = "SELECT * FROM sma_supplier_invoice WHERE del != 'Y'";
		if (!empty($from_date) && !empty($to_date)) {
			$sql .= " AND invoice_date >= '$from_date' AND invoice_date <= '$to_date'";
		}
		if (!empty($supplier_id)) {
			$sql .= " AND suplier_name = '$supplier_id'";
		}
	}

	// Remove pagination limit if present
	$sql = preg_replace('/\s+LIMIT\s+\d+(\s*,\s*\d+)?/i', '', $sql);
	if (stripos($sql, 'order by') === false) {
		$sql .= ' ORDER BY invoice_date DESC, id DESC';
	}

	$result = mysqli_query($con, $sql);
	$error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}

	$ln = 0;
	while($row = mysqli_fetch_array($result)){
		$del    = $row['del'] ?? '';
		$status = $row['status'] ?? '';
		if ($del == 'Y') {
			continue;
		}

		$si_id               = $row['id'] ?? '';
		$invoice_date_raw    = $row['invoice_date'] ?? '';
		$due_date_raw        = $row['due_date'] ?? '';
		$supplier_id_row     = $row['suplier_name'] ?? '';
		$supplier_invoice_no = $row['supplier_invoice_no'] ?? '';
		$credit_days         = $row['credit_days'] ?? '';
		$trans_type          = $row['trans_type'] ?? '';
		$our_po_ref_no       = $row['our_po_ref_no'] ?? '';

		$invoice_date = (!empty($invoice_date_raw) && $invoice_date_raw != '1970-01-01' && $invoice_date_raw != '0000-00-00') ? date('d-m-Y', strtotime($invoice_date_raw)) : '';
		$due_date     = (!empty($due_date_raw) && $due_date_raw != '1970-01-01' && $due_date_raw != '0000-00-00') ? date('d-m-Y', strtotime($due_date_raw)) : '';
		if ($credit_days == '0') {
			$credit_days = '';
		}

		// Workflow Type lookup
		$workflow_type = '';
		if (!empty($trans_type)) {
			$sql_wf = "SELECT workflow_type FROM `sma_workflow_type` WHERE id = '$trans_type'";
			$res_wf = mysqli_query($con, $sql_wf);
			if ($res_wf && $r_wf = mysqli_fetch_array($res_wf)) {
				$workflow_type = $r_wf['workflow_type'] ?? '';
			}
		}

		// Supplier lookup
		$supplier_name     = '';
		$party_pan_number  = '';
		$party_gst_number  = '';
		$party_msme_number = '';
		if (!empty($supplier_id_row)) {
			$sql_sup = "SELECT party_name, party_pan_number, party_gst_number, party_msme_number FROM `sma_party_mst` WHERE id = '$supplier_id_row'";
			$res_sup = mysqli_query($con, $sql_sup);
			if ($res_sup && $r_sup = mysqli_fetch_array($res_sup)) {
				$supplier_name     = $r_sup['party_name'] ?? '';
				$party_pan_number  = $r_sup['party_pan_number'] ?? '';
				$party_gst_number  = $r_sup['party_gst_number'] ?? '';
				$party_msme_number = $r_sup['party_msme_number'] ?? '';
			}
		}

		// PO number & info lookup
		$po_number_display = $our_po_ref_no;
		$po_memo_ref = '';
		if (!empty($our_po_ref_no)) {
			$sql_po = "SELECT id, po_number, approval_memo_ref FROM `sma_purchase_order` WHERE id = '$our_po_ref_no'";
			$res_po = mysqli_query($con, $sql_po);
			if ($res_po && $r_po = mysqli_fetch_array($res_po)) {
				$po_number_display = $r_po['po_number'] ?? $our_po_ref_no;
				$po_memo_ref       = $r_po['approval_memo_ref'] ?? '';
			}
		}

		// Approved By lookup
		$approver_name = '';
		if (!empty($si_id)) {
			$sql_ap = "SELECT create_by FROM `workflow_history` WHERE doc_type = 'SI' AND doc_id = '$si_id' AND status IN ('Approved', 'Submitted') ORDER BY id DESC LIMIT 1";
			$res_ap = mysqli_query($con, $sql_ap);
			if ($res_ap && $r_ap = mysqli_fetch_array($res_ap)) {
				$c_by = $r_ap['create_by'] ?? '';
				if (!empty($c_by)) {
					$sql_u = "SELECT username FROM `sma_user` WHERE id = '$c_by' OR userid = '$c_by'";
					$res_u = mysqli_query($con, $sql_u);
					if ($res_u && $r_u = mysqli_fetch_array($res_u)) {
						$approver_name = $r_u['username'] ?? '';
					}
				}
			}
		}

		// Invoice Details
		$sql_dtl = "SELECT * FROM `sma_supplier_invoice_details` WHERE si_hdr_id = '$si_id' AND qty > 0";
		$res_dtl = mysqli_query($con, $sql_dtl);
		$has_dtl = false;

		while ($rw = mysqli_fetch_array($res_dtl)) {
			$has_dtl = true;
			$ln++;
			$grn_no         = $rw['grn_no'] ?? '';
			$qty            = floatval($rw['qty'] ?? 0);
			$unit_rate      = floatval($rw['rate'] ?? 0);
			$gst            = floatval($rw['gst'] ?? 0);
			$unit           = $rw['unit'] ?? '';
			$description    = $rw['description'] ?? '';
			$account_year_r = $rw['account_year'] ?? '';
			$company_id_dtl = $rw['company_id'] ?? '';
			$budget_id      = $rw['budget_id'] ?? 0;
			$material_id    = $rw['material_id'] ?? 0;

			$account_year_map = [
				'1' => '2017-2018', '2' => '2018-2019', '3' => '2019-2020',
				'4' => '2020-2021', '5' => '2021-2022', '6' => '2022-2023',
				'7' => '2023-2024', '8' => '2024-2025', '9' => '2025-2026', '10' => '2026-2027'
			];
			$account_year = $account_year_map[$account_year_r] ?? $account_year_r;

			$total_amt = round(($qty * $unit_rate) + (($qty * $unit_rate) * $gst / 100), 2);

			// Company lookup
			$comp_name = '';
			if (!empty($company_id_dtl)) {
				$sql_c = "SELECT comp_code, comp_name FROM `company` WHERE comp_id = '$company_id_dtl'";
				$res_c = mysqli_query($con, $sql_c);
				if ($res_c && $r_c = mysqli_fetch_array($res_c)) {
					$comp_name = !empty($r_c['comp_code']) ? $r_c['comp_code'] : ($r_c['comp_name'] ?? '');
				}
			}

			// Material / Product lookup
			$product_name = '';
			$prod_b_head = '';
			$prod_b_name = '';
			if (!empty($material_id)) {
				$sql_mat = "SELECT name, uom, budget_head, budget_name FROM `sma_product` WHERE id = '$material_id'";
				$res_mat = mysqli_query($con, $sql_mat);
				if ($res_mat && $r_mat = mysqli_fetch_array($res_mat)) {
					$product_name = $r_mat['name'] ?? '';
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

			// 1. From sma_budget via budget_id
			if (!empty($budget_id)) {
				$sql_b = "SELECT a.budget_head, a.budget_code, a.account_year, b.name AS budget_name 
				          FROM `sma_budget` a 
				          LEFT JOIN `sma_budget_name` b ON a.budget_name = b.id 
				          WHERE a.id = '$budget_id'";
				$res_b = mysqli_query($con, $sql_b);
				if ($res_b && $r_b = mysqli_fetch_array($res_b)) {
					$budget_name = $r_b['budget_name'] ?? '';
					$b_sub_id    = $r_b['budget_head'] ?? '';
					$budget_code = $r_b['budget_code'] ?? '';
					if (empty($account_year) && !empty($r_b['account_year'])) {
						$account_year = $r_b['account_year'];
					}

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

			// 3. Fallback from Linked PO -> NOA -> PR
			if ((empty($budget_name) || empty($budget_head)) && !empty($our_po_ref_no)) {
				$sql_poi = "SELECT budget_id, product_id FROM `sma_po_items` WHERE purchase_id = '$our_po_ref_no' AND (budget_id > 0 OR product_id > 0) ORDER BY (budget_id > 0) DESC LIMIT 1";
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
				}
			}

			$excel_rows[] = [
				$ln,
				$si_id,
				$supplier_name,
				$party_pan_number,
				$party_gst_number,
				$party_msme_number,
				$supplier_invoice_no,
				$invoice_date,
				$po_number_display,
				$credit_days,
				$due_date,
				$grn_no,
				$product_name,
				$description,
				$account_year,
				$comp_name,
				$budget_name,
				$budget_head,
				$budget_code,
				$unit,
				$qty,
				$unit_rate,
				$gst,
				$total_amt,
				$approver_name,
				$workflow_type,
				$status
			];
		}

		if (!$has_dtl) {
			$ln++;
			$excel_rows[] = [
				$ln,
				$si_id,
				$supplier_name,
				$party_pan_number,
				$party_gst_number,
				$party_msme_number,
				$supplier_invoice_no,
				$invoice_date,
				$po_number_display,
				$credit_days,
				$due_date,
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
				$approver_name,
				$workflow_type,
				$status
			];
		}
	}

	if ($prn == 'excel') {
		$fl_name = 'Supplier_Invoice_Summary_' . date('Y-m-d') . '.xlsx';
		\Shuchkin\SimpleXLSXGen::fromArray($excel_rows)->downloadAs($fl_name);
		exit();
	}
}
?>