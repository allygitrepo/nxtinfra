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
		$title_banner = 'Purchase Order Summary Report from ' . $from_date_dmy . ' TO ' . $to_date_dmy;
	} else if (!empty($from_date_dmy)) {
		$title_banner = 'Purchase Order Summary Report from ' . $from_date_dmy;
	} else if (!empty($to_date_dmy)) {
		$title_banner = 'Purchase Order Summary Report up to ' . $to_date_dmy;
	} else {
		$title_banner = 'Purchase Order Summary Report';
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
		$sql = "SELECT * FROM sma_purchase_order WHERE del != 'Y' AND approval_status != 'Rejected'";
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

		$po_dated = (!empty($dated_raw) && $dated_raw != '1970-01-01' && $dated_raw != '0000-00-00') ? date('d-m-Y', strtotime($dated_raw)) : '';

		// Date from workflow history if available
		if (!empty($pur_id)) {
			$s1 = "SELECT create_date FROM `workflow_history` WHERE doc_id = '$pur_id' AND doc_type = 'PO' ORDER BY id DESC LIMIT 1";
			$res_w = mysqli_query($con, $s1);
			if ($res_w && $r1 = mysqli_fetch_array($res_w)) {
				if (!empty($r1['create_date']) && $r1['create_date'] != '1970-01-01' && $r1['create_date'] != '0000-00-00') {
					$po_dated = date('d-m-Y', strtotime($r1['create_date']));
				}
			}
		}

		// Company lookup
		$comp_name = '';
		$comp_code = '';
		if (!empty($company_id_row)) {
			$sql_c = "SELECT comp_code, comp_name FROM `company` WHERE comp_id = '$company_id_row'";
			$res_c = mysqli_query($con, $sql_c);
			if ($res_c && $com = mysqli_fetch_array($res_c)) {
				$comp_name = $com['comp_name'] ?? '';
				$comp_code = !empty($com['comp_code']) ? $com['comp_code'] : $comp_name;
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
		$party_address = '';
		if (!empty($to_supplier)) {
			$sql_p = "SELECT party_name, party_address_1, party_address_2, party_address_3, party_pincode FROM `sma_party_mst` WHERE id = '$to_supplier'";
			$res_p = mysqli_query($con, $sql_p);
			if ($res_p && $rw_p = mysqli_fetch_array($res_p)) {
				$party_name = $rw_p['party_name'] ?? '';
				$addr_parts = array_filter([$rw_p['party_address_1'] ?? '', $rw_p['party_address_2'] ?? '', $rw_p['party_address_3'] ?? '', $rw_p['party_pincode'] ?? '']);
				$party_address = implode(', ', $addr_parts);
			}
		}

		// Budget Group & Budget Sub Group (Comprehensive Multi-Tier Fallback)
		$budget_name = '';
		$budget_head = '';

		// 1. From sma_po_items
		if (!empty($pur_id)) {
			$sql_pi = "SELECT budget_id, product_id FROM `sma_po_items` WHERE purchase_id = '$pur_id' AND (budget_id > 0 OR product_id > 0) ORDER BY (budget_id > 0) DESC LIMIT 1";
			$res_pi = mysqli_query($con, $sql_pi);
			if ($res_pi && $r_pi = mysqli_fetch_array($res_pi)) {
				$b_id    = $r_pi['budget_id'] ?? 0;
				$prod_id = $r_pi['product_id'] ?? 0;

				if (!empty($b_id)) {
					$sql_b = "SELECT a.budget_head, a.budget_code, b.name AS budget_name 
					          FROM `sma_budget` a 
					          LEFT JOIN `sma_budget_name` b ON a.budget_name = b.id 
					          WHERE a.id = '$b_id'";
					$res_b = mysqli_query($con, $sql_b);
					if ($res_b && $r_b = mysqli_fetch_array($res_b)) {
						$budget_name = $r_b['budget_name'] ?? '';
						$b_sub_id    = $r_b['budget_head'] ?? '';

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

				if ((empty($budget_name) || empty($budget_head)) && !empty($prod_id)) {
					$sql_p = "SELECT budget_head, budget_name FROM `sma_product` WHERE id = '$prod_id'";
					$res_p = mysqli_query($con, $sql_p);
					if ($res_p && $r_p = mysqli_fetch_array($res_p)) {
						$p_head = $r_p['budget_head'] ?? '';
						$p_name = $r_p['budget_name'] ?? '';

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
			}
		}

		// 2. From Linked NOA / PR
		if ((empty($budget_name) || empty($budget_head)) && !empty($approval_memo_ref)) {
			$sql_noa = "SELECT against_indent_no, budget_head FROM `sma_approval_memo` WHERE id = '$approval_memo_ref'";
			$res_noa = mysqli_query($con, $sql_noa);
			if ($res_noa && $r_noa = mysqli_fetch_array($res_noa)) {
				$noa_indent = $r_noa['against_indent_no'] ?? '';
				$noa_b_head = $r_noa['budget_head'] ?? '';

				if (!empty($noa_indent)) {
					$sql_pri = "SELECT p.budget_head, p.budget_name 
					            FROM `sma_purchase_req_items` pri 
					            LEFT JOIN `sma_product` p ON pri.product_id = p.id 
					            WHERE pri.purchase_req_id = '$noa_indent' AND (p.budget_head > 0 OR p.budget_name > 0) 
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

				if ((empty($budget_name) || empty($budget_head)) && !empty($noa_b_head)) {
					$sql_sub = "SELECT budget_head, budget_name FROM `sma_budget_subgroup` WHERE id = '$noa_b_head'";
					$res_sub = mysqli_query($con, $sql_sub);
					if ($res_sub && $r_sub = mysqli_fetch_array($res_sub)) {
						if (empty($budget_head)) {
							$budget_head = $r_sub['budget_head'] ?? '';
						}
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

		// Calculate PO Total Amount
		$total_po_amt = 0;
		if (!empty($pur_id)) {
			$sql_items = "SELECT quantity, unit_rate, gst FROM `sma_po_items` WHERE purchase_id = '$pur_id'";
			$res_items = mysqli_query($con, $sql_items);
			while ($rw_it = mysqli_fetch_array($res_items)) {
				$qty  = floatval($rw_it['quantity'] ?? 0);
				$rate = floatval($rw_it['unit_rate'] ?? 0);
				$gst  = floatval($rw_it['gst'] ?? 0);
				$total_po_amt += round(($qty * $rate) + (($qty * $rate) * $gst / 100), 2);
			}
		}

		// Calculate Used PO Amount from Supplier Invoices
		$tot_supp_amt = 0;
		if (!empty($pur_id)) {
			$sql_supp = "SELECT id FROM `sma_supplier_invoice` WHERE del != 'Y' AND approval_status != 'Rejected' AND our_po_ref_no = '$pur_id'";
			$res_supp = mysqli_query($con, $sql_supp);
			while ($supphdr = mysqli_fetch_array($res_supp)) {
				$si_hdr_id = $supphdr['id'];
				$sql_si = "SELECT qty, rate, gst FROM `sma_supplier_invoice_details` WHERE si_hdr_id = '$si_hdr_id'";
				$res_si = mysqli_query($con, $sql_si);
				while ($supp = mysqli_fetch_array($res_si)) {
					$s_qty  = floatval($supp['qty'] ?? 0);
					$s_rate = floatval($supp['rate'] ?? 0);
					$s_gst  = floatval($supp['gst'] ?? 0);
					$tot_supp_amt += round(($s_rate * $s_qty) + (($s_rate * $s_qty) * $s_gst / 100), 2);
				}
			}
		}

		$bal_po_amt = round($total_po_amt - $tot_supp_amt, 2);

		$excel_rows[] = [
			$ln,
			$po_number,
			$comp_code,
			$department_name,
			$loc_name,
			$po_dated,
			$budget_name,
			$budget_head,
			$party_name,
			$party_address,
			$subject,
			$total_po_amt,
			$tot_supp_amt,
			$bal_po_amt,
			$status
		];
	}

	if ($prn == 'excel') {
		$fl_name = 'PO_Summary_Report_' . date('Y-m-d') . '.xlsx';
		\Shuchkin\SimpleXLSXGen::fromArray($excel_rows)->downloadAs($fl_name);
		exit();
	}
}
?>