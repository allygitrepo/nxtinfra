<?php
ini_set('max_execution_time', 0);
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
		$title_banner = 'Supplier Invoice Register from ' . $from_date_dmy . ' TO ' . $to_date_dmy;
	} else if (!empty($from_date_dmy)) {
		$title_banner = 'Supplier Invoice Register from ' . $from_date_dmy;
	} else if (!empty($to_date_dmy)) {
		$title_banner = 'Supplier Invoice Register up to ' . $to_date_dmy;
	} else {
		$title_banner = 'Supplier Invoice Register';
	}

	$excel_rows = [];
	$excel_rows[] = [$title_banner];
	$excel_rows[] = [
		'Sr.No.',
		'SI.No.',
		'Company',
		'Supplier Name',
		'Supplier Inv.No.',
		'Created Date',
		'Invoice Date',
		'PO.Ref.No.',
		'PO Created Date',
		'PO Approved Date',
		'Due Date',
		'Invoice Received Date',
		'Approved Date',
		'GRN No.',
		'GRN Created Date',
		'GRN Approved Date',
		'Material Name',
		'Description',
		'Account Year',
		'Budget Group',
		'Budget Sub Group',
		'Budget Code',
		'Unit',
		'Qty.',
		'Rate',
		'GST.',
		'Total.',
		'Payment Approved Date',
		'UTR.No.',
		'Paid Date',
		'Approved By',
		'Workflow Type',
		'Pending With',
		'Status',
		'Decision',
		'Narration'
	];

	if (!empty($_SESSION['sqlex'])) {
		$sql = $_SESSION['sqlex'];
	} else {
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
		$del = $row['del'] ?? '';
		if ($del == 'Y') {
			continue;
		}

		$si_id               = $row['id'] ?? '';
		$supplier_id_row     = $row['suplier_name'] ?? '';
		$supplier_invoice_no = $row['supplier_invoice_no'] ?? '';
		$invoice_date_raw    = $row['invoice_date'] ?? '';
		$due_date_raw        = $row['due_date'] ?? '';
		$inv_rec_date_raw    = $row['invoice_received_date'] ?? ($row['inv_rec_date'] ?? '');
		$created_date_raw    = $row['created_date'] ?? ($row['draft_date'] ?? '');
		$status              = $row['status'] ?? '';
		$our_po_ref_no       = $row['our_po_ref_no'] ?? '';
		$trans_type          = $row['trans_type'] ?? '';
		$decision            = $row['approval_status'] ?? ($row['decision'] ?? '');
		$remarks             = $row['tally_narration'] ?? ($row['remarks'] ?? '');
		$draft_by            = $row['draft_by'] ?? '';

		$invoice_date          = (!empty($invoice_date_raw) && $invoice_date_raw != '1970-01-01' && $invoice_date_raw != '0000-00-00') ? date('d-m-Y', strtotime($invoice_date_raw)) : '';
		$due_date              = (!empty($due_date_raw) && $due_date_raw != '1970-01-01' && $due_date_raw != '0000-00-00') ? date('d-m-Y', strtotime($due_date_raw)) : '';
		$invoice_received_date = (!empty($inv_rec_date_raw) && $inv_rec_date_raw != '1970-01-01' && $inv_rec_date_raw != '0000-00-00') ? date('d-m-Y', strtotime($inv_rec_date_raw)) : '';
		$created_date          = (!empty($created_date_raw) && $created_date_raw != '1970-01-01' && $created_date_raw != '0000-00-00') ? date('d-m-Y', strtotime($created_date_raw)) : '';

		// Supplier lookup
		$supplier_name = '';
		if (!empty($supplier_id_row)) {
			$sql_sup = "SELECT party_name FROM `sma_party_mst` WHERE id = '$supplier_id_row'";
			$res_sup = mysqli_query($con, $sql_sup);
			if ($res_sup && $r_sup = mysqli_fetch_array($res_sup)) {
				$supplier_name = $r_sup['party_name'] ?? '';
			}
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

		// PO info lookup (PO Number, PO Created Date, PO Approved Date)
		$po_number_display = $our_po_ref_no;
		$po_created_date   = '';
		$po_approved_date  = '';
		if (!empty($our_po_ref_no)) {
			$sql_po = "SELECT id, po_number, dated, draft_date, project FROM `sma_purchase_order` WHERE id = '$our_po_ref_no'";
			$res_po = mysqli_query($con, $sql_po);
			if ($res_po && $r_po = mysqli_fetch_array($res_po)) {
				$po_number_display = $r_po['po_number'] ?? $our_po_ref_no;
				$p_dt = !empty($r_po['dated']) ? $r_po['dated'] : ($r_po['draft_date'] ?? '');
				if (!empty($p_dt) && $p_dt != '1970-01-01' && $p_dt != '0000-00-00') {
					$po_created_date = date('d-m-Y', strtotime($p_dt));
				}
			}
			$sql_pow = "SELECT create_date, approved_date FROM `workflow_history` WHERE doc_type = 'PO' AND doc_id = '$our_po_ref_no' ORDER BY id DESC LIMIT 1";
			$res_pow = mysqli_query($con, $sql_pow);
			if ($res_pow && $r_pow = mysqli_fetch_array($res_pow)) {
				if (empty($po_created_date) && !empty($r_pow['create_date']) && $r_pow['create_date'] != '1970-01-01' && $r_pow['create_date'] != '0000-00-00') {
					$po_created_date = date('d-m-Y', strtotime($r_pow['create_date']));
				}
				if (!empty($r_pow['approved_date']) && $r_pow['approved_date'] != '1970-01-01' && $r_pow['approved_date'] != '0000-00-00') {
					$po_approved_date = date('d-m-Y', strtotime($r_pow['approved_date']));
				}
			}
		}

		// Approved Date & Approved By lookup for SI
		$approved_date = '';
		$approver_name = '';
		if (!empty($si_id)) {
			$sql_ap = "SELECT create_by, approved_date FROM `workflow_history` WHERE doc_type = 'SI' AND doc_id = '$si_id' AND status IN ('Approved', 'Submitted') ORDER BY id DESC LIMIT 1";
			$res_ap = mysqli_query($con, $sql_ap);
			if ($res_ap && $r_ap = mysqli_fetch_array($res_ap)) {
				if (!empty($r_ap['approved_date']) && $r_ap['approved_date'] != '1970-01-01' && $r_ap['approved_date'] != '0000-00-00') {
					$approved_date = date('d-m-Y', strtotime($r_ap['approved_date']));
				}
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

		// Pending approver lookup
		$pending_by_name = '';
		for ($i = 1; $i <= 8; $i++) {
			if (($row['approver_' . $i . '_status'] ?? '') == 'Submitted') {
				$p_id = $row['approver_' . $i] ?? '';
				if (!empty($p_id)) {
					$sql_p = "SELECT username FROM `sma_user` WHERE id = '$p_id' OR userid = '$p_id'";
					$res_p = mysqli_query($con, $sql_p);
					if ($res_p && $r_p = mysqli_fetch_array($res_p)) {
						$pending_by_name = $r_p['username'] ?? '';
					}
				}
				break;
			}
		}

		// Payment UTR & Paid Date lookup from payment_header / payment_details
		$payment_approved_date = '';
		$utr_no                = '';
		$paid_date             = '';
		if (!empty($si_id)) {
			$sql_pay = "SELECT a.id AS py_id, a.utr_no, a.paid_date 
			            FROM `payment_header` a 
			            LEFT JOIN `payment_details` b ON a.id = b.payment_hdr_id 
			            WHERE a.st_flag = 'S' AND b.supp_id = '$si_id' AND a.del != 'Y' 
			            LIMIT 1";
			$res_pay = mysqli_query($con, $sql_pay);
			if ($res_pay && $r_pay = mysqli_fetch_array($res_pay)) {
				$py_id  = $r_pay['py_id'] ?? '';
				$utr_no = $r_pay['utr_no'] ?? '';
				if (!empty($r_pay['paid_date']) && $r_pay['paid_date'] != '1970-01-01' && $r_pay['paid_date'] != '0000-00-00') {
					$paid_date = date('d-m-Y', strtotime($r_pay['paid_date']));
				}

				if (!empty($py_id)) {
					$sql_pyw = "SELECT approved_date, create_date FROM `workflow_history` WHERE doc_type = 'PY' AND doc_id = '$py_id' AND status IN ('Approved', 'Submitted') ORDER BY id DESC LIMIT 1";
					$res_pyw = mysqli_query($con, $sql_pyw);
					if ($res_pyw && $r_pyw = mysqli_fetch_array($res_pyw)) {
						$p_date = !empty($r_pyw['approved_date']) ? $r_pyw['approved_date'] : ($r_pyw['create_date'] ?? '');
						if (!empty($p_date) && $p_date != '1970-01-01' && $p_date != '0000-00-00') {
							$payment_approved_date = date('d-m-Y', strtotime($p_date));
						}
					}
				}
			}
		}

		// Invoice Details
		$sql_dtl = "SELECT * FROM `sma_supplier_invoice_details` WHERE si_hdr_id = '$si_id'";
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
			$material_name_dtl = $rw['material_name'] ?? '';

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

			// GRN Created & Approved Dates lookup from sma_grn_srn
			$grn_created_date  = '';
			$grn_approved_date = '';
			if (!empty($grn_no)) {
				$sql_grn = "SELECT id, draft_date, received_date FROM `sma_grn_srn` WHERE id = '$grn_no' LIMIT 1";
				$res_grn = mysqli_query($con, $sql_grn);
				if ($res_grn && $r_grn = mysqli_fetch_array($res_grn)) {
					$grn_id = $r_grn['id'];
					$g_date = !empty($r_grn['draft_date']) ? $r_grn['draft_date'] : ($r_grn['received_date'] ?? '');
					if (!empty($g_date) && $g_date != '1970-01-01' && $g_date != '0000-00-00') {
						$grn_created_date = date('d-m-Y', strtotime($g_date));
					}
					$sql_grnw = "SELECT approved_date FROM `workflow_history` WHERE doc_type = 'GRN' AND doc_id = '$grn_id' AND status = 'Approved' ORDER BY id DESC LIMIT 1";
					$res_grnw = mysqli_query($con, $sql_grnw);
					if ($res_grnw && $r_grnw = mysqli_fetch_array($res_grnw)) {
						if (!empty($r_grnw['approved_date']) && $r_grnw['approved_date'] != '1970-01-01' && $r_grnw['approved_date'] != '0000-00-00') {
							$grn_approved_date = date('d-m-Y', strtotime($r_grnw['approved_date']));
						}
					}
				}
			}

			// Material / Product lookup
			$product_name = $material_name_dtl;
			$prod_b_head = '';
			$prod_b_name = '';
			if (!empty($material_id)) {
				$sql_mat = "SELECT name, uom, budget_head, budget_name FROM `sma_product` WHERE id = '$material_id'";
				$res_mat = mysqli_query($con, $sql_mat);
				if ($res_mat && $r_mat = mysqli_fetch_array($res_mat)) {
					$product_name = !empty($r_mat['name']) ? $r_mat['name'] : $product_name;
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
				$comp_name,
				$supplier_name,
				$supplier_invoice_no,
				$created_date,
				$invoice_date,
				$po_number_display,
				$po_created_date,
				$po_approved_date,
				$due_date,
				$invoice_received_date,
				$approved_date,
				$grn_no,
				$grn_created_date,
				$grn_approved_date,
				$product_name,
				$description,
				$account_year,
				$budget_name,
				$budget_head,
				$budget_code,
				$unit,
				$qty,
				$unit_rate,
				$gst,
				$total_amt,
				$payment_approved_date,
				$utr_no,
				$paid_date,
				$approver_name,
				$workflow_type,
				$pending_by_name,
				$status,
				$decision,
				$remarks
			];
		}

		if (!$has_dtl) {
			$ln++;
			$excel_rows[] = [
				$ln,
				$si_id,
				'',
				$supplier_name,
				$supplier_invoice_no,
				$created_date,
				$invoice_date,
				$po_number_display,
				$po_created_date,
				$po_approved_date,
				$due_date,
				$invoice_received_date,
				$approved_date,
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
				$payment_approved_date,
				$utr_no,
				$paid_date,
				$approver_name,
				$workflow_type,
				$pending_by_name,
				$status,
				$decision,
				$remarks
			];
		}
	}

	if ($prn == 'excel') {
		$fl_name = 'Supplier_Invoice_Report_' . date('Y-m-d') . '.xlsx';
		\Shuchkin\SimpleXLSXGen::fromArray($excel_rows)->downloadAs($fl_name);
		exit();
	}
}
?>