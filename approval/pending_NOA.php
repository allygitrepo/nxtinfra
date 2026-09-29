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
		$title_banner = 'Pending NOA Register from ' . $from_date_dmy . ' TO ' . $to_date_dmy;
	} else if (!empty($from_date_dmy)) {
		$title_banner = 'Pending NOA Register from ' . $from_date_dmy;
	} else if (!empty($to_date_dmy)) {
		$title_banner = 'Pending NOA Register up to ' . $to_date_dmy;
	} else {
		$title_banner = 'Pending NOA Register';
	}

	$excel_rows = [];
	$excel_rows[] = [$title_banner];
	$excel_rows[] = [
		'Sr.No.',
		'SPV Name',
		'Department',
		'Location',
		'NOA No.',
		'Dated',
		'Budget Group',
		'Budget Sub Group',
		'Budget Code',
		'Supplier Name',
		'Description',
		'Amount',
		'Created By',
		'Pending for Approval by',
		'Status'
	];

	if (!empty($_SESSION['sqlex'])) {
		$sql = $_SESSION['sqlex'];
	} else {
		$sql = "SELECT * FROM sma_approval_memo WHERE del != 'Y'";
		if (!empty($from_date) && !empty($to_date)) {
			$sql .= " AND dated >= '$from_date' AND dated <= '$to_date'";
		}
		if (!empty($company_id)) {
			$sql .= " AND (company = '$company_id' OR project = '$company_id')";
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
		$app_id            = $row['id'] ?? '';
		$ap_number         = $row['ap_number'] ?? '';
		$dated_raw         = $row['dated'] ?? '';
		$company_id_row    = $row['company'] ?? '';
		$department_id_row = $row['department'] ?? '';
		$location_id_row   = $row['location'] ?? '';
		$project_id_row    = $row['project'] ?? '';
		$against_indent_no = $row['against_indent_no'] ?? '';
		$memo_budget_head  = $row['budget_head'] ?? '';
		$subject           = $row['subject'] ?? '';
		$status            = $row['status'] ?? '';
		$cost              = $row['cost'] ?? '';
		$draft_by          = $row['draft_by'] ?? '';

		$dated = (!empty($dated_raw) && $dated_raw != '1970-01-01' && $dated_raw != '0000-00-00') ? date('d-m-Y', strtotime($dated_raw)) : '';

		// Draft By lookup
		$draft_by_name = $draft_by;
		if (!empty($draft_by)) {
			$sql_u = "SELECT username FROM `sma_user` WHERE userid = '$draft_by' OR id = '$draft_by'";
			$res_u = mysqli_query($con, $sql_u);
			if ($res_u && $com = mysqli_fetch_array($res_u)) {
				$draft_by_name = $com['username'] ?? $draft_by;
			}
		}

		// Pending approver lookup
		$pending_by = '';
		for ($i = 1; $i <= 8; $i++) {
			if (($row['approver_' . $i . '_status'] ?? '') == 'Submitted') {
				$pending_by = $row['approver_' . $i] ?? '';
				break;
			}
		}
		$pending_by_name = '';
		if (!empty($pending_by)) {
			$sql_p = "SELECT username FROM `sma_user` WHERE id = '$pending_by' OR userid = '$pending_by'";
			$res_p = mysqli_query($con, $sql_p);
			if ($res_p && $r2 = mysqli_fetch_array($res_p)) {
				$pending_by_name = $r2['username'] ?? '';
			}
		}

		// Company lookup
		$comp_name = '';
		$c_id = !empty($company_id_row) ? $company_id_row : $project_id_row;
		if (!empty($c_id)) {
			$sql_c = "SELECT comp_code, comp_name FROM `company` WHERE comp_id = '$c_id'";
			$res_c = mysqli_query($con, $sql_c);
			if ($res_c && $com = mysqli_fetch_array($res_c)) {
				$comp_name = !empty($com['comp_code']) ? $com['comp_code'] : ($com['comp_name'] ?? '');
			}
		}
		if (empty($comp_name) && !empty($against_indent_no)) {
			$sql_c2 = "SELECT b.comp_code, b.comp_name FROM `sma_purchase_req` a LEFT JOIN `company` b ON a.company_id = b.comp_id WHERE a.id = '$against_indent_no'";
			$res_c2 = mysqli_query($con, $sql_c2);
			if ($res_c2 && $com2 = mysqli_fetch_array($res_c2)) {
				$comp_name = !empty($com2['comp_code']) ? $com2['comp_code'] : ($com2['comp_name'] ?? '');
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
		if (empty($department_name) && !empty($against_indent_no)) {
			$sql_d2 = "SELECT b.name FROM `sma_purchase_req` a LEFT JOIN `sma_department` b ON a.department_id = b.id WHERE a.id = '$against_indent_no'";
			$res_d2 = mysqli_query($con, $sql_d2);
			if ($res_d2 && $deps2 = mysqli_fetch_array($res_d2)) {
				$department_name = $deps2['name'] ?? '';
			}
		}

		// Location lookup
		$loc_name = '';
		$loc_id = !empty($location_id_row) ? $location_id_row : $project_id_row;
		if (!empty($loc_id)) {
			$sql_l = "SELECT loc_name FROM `sma_location` WHERE id = '$loc_id'";
			$res_l = mysqli_query($con, $sql_l);
			if ($res_l && $lc = mysqli_fetch_array($res_l)) {
				$loc_name = $lc['loc_name'] ?? '';
			}
		}
		if (empty($loc_name) && !empty($against_indent_no)) {
			$sql_l2 = "SELECT b.loc_name FROM `sma_purchase_req` a LEFT JOIN `sma_location` b ON a.project_id = b.id WHERE a.id = '$against_indent_no'";
			$res_l2 = mysqli_query($con, $sql_l2);
			if ($res_l2 && $lc2 = mysqli_fetch_array($res_l2)) {
				$loc_name = $lc2['loc_name'] ?? '';
			}
		}

		// Budget Group & Budget Sub Group & Budget Code (Comprehensive Multi-Tier Fallback)
		$budget_name = '';
		$budget_head = '';
		$budget_code = '';

		// 1. From sma_approval_items
		if (!empty($app_id)) {
			$sql_ai = "SELECT budget_id, product_id FROM `sma_approval_items` WHERE approval_hdr_id = '$app_id' AND (budget_id > 0 OR product_id > 0) ORDER BY (budget_id > 0) DESC LIMIT 1";
			$res_ai = mysqli_query($con, $sql_ai);
			if ($res_ai && $r_ai = mysqli_fetch_array($res_ai)) {
				$b_id    = $r_ai['budget_id'] ?? 0;
				$prod_id = $r_ai['product_id'] ?? 0;

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

		// 2. From Linked Purchase Requisition (against_indent_no)
		if ((empty($budget_name) || empty($budget_head)) && !empty($against_indent_no)) {
			$sql_pri = "SELECT p.budget_head, p.budget_name 
			            FROM `sma_purchase_req_items` pri 
			            LEFT JOIN `sma_product` p ON pri.product_id = p.id 
			            WHERE pri.purchase_req_id = '$against_indent_no' AND (p.budget_head > 0 OR p.budget_name > 0) 
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

		// 3. From sma_approval_memo.budget_head
		if ((empty($budget_name) || empty($budget_head)) && !empty($memo_budget_head)) {
			$sql_sub = "SELECT budget_head, budget_name FROM `sma_budget_subgroup` WHERE id = '$memo_budget_head'";
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

		// Supplier Name & Quoted Amount lookup
		$supplier_name = '';
		$quoted_amount = 0;
		if (!empty($app_id)) {
			$sql_ad = "SELECT d.supplier_name, d.values, d.vendor_selected, p.party_name 
			           FROM `sma_approval_details` d 
			           LEFT JOIN `sma_party_mst` p ON d.supplier_name = p.id 
			           WHERE d.approval_hdr_id = '$app_id' AND d.vendor_selected = 'Y'";
			$res_ad = mysqli_query($con, $sql_ad);
			$p_names = [];
			while ($r_ad = mysqli_fetch_array($res_ad)) {
				if (!empty($r_ad['party_name'])) {
					$p_names[] = $r_ad['party_name'];
				}
				$quoted_amount += floatval($r_ad['values'] ?? 0);
			}
			if (!empty($p_names)) {
				$supplier_name = implode(', ', $p_names);
			} else {
				$sql_ad2 = "SELECT d.supplier_name, d.values, p.party_name 
				            FROM `sma_approval_details` d 
				            LEFT JOIN `sma_party_mst` p ON d.supplier_name = p.id 
				            WHERE d.approval_hdr_id = '$app_id' 
				            LIMIT 1";
				$res_ad2 = mysqli_query($con, $sql_ad2);
				if ($res_ad2 && $r_ad2 = mysqli_fetch_array($res_ad2)) {
					$supplier_name = !empty($r_ad2['party_name']) ? $r_ad2['party_name'] : ($r_ad2['supplier_name'] ?? '');
					$quoted_amount = floatval($r_ad2['values'] ?? 0);
				}
			}
		}
		if (empty($quoted_amount) && !empty($app_id)) {
			$sql_ai = "SELECT quantity, unit_rate, gst FROM `sma_approval_items` WHERE approval_hdr_id = '$app_id'";
			$res_ai = mysqli_query($con, $sql_ai);
			while ($r_ai = mysqli_fetch_array($res_ai)) {
				$qty  = floatval($r_ai['quantity'] ?? 0);
				$rate = floatval($r_ai['unit_rate'] ?? 0);
				$gst  = floatval($r_ai['gst'] ?? 0);
				$quoted_amount += round(($qty * $rate) + ((($qty * $rate) * $gst) / 100), 2);
			}
		}
		if (empty($quoted_amount) && !empty($cost)) {
			$quoted_amount = floatval($cost);
		}

		$excel_rows[] = [
			$ln,
			$comp_name,
			$department_name,
			$loc_name,
			$ap_number,
			$dated,
			$budget_name,
			$budget_head,
			$budget_code,
			$supplier_name,
			$subject,
			$quoted_amount,
			$draft_by_name,
			$pending_by_name,
			$status
		];
	}

	if ($prn == 'excel') {
		$fl_name = 'pending_noa_' . date('Y-m-d') . '.xlsx';
		\Shuchkin\SimpleXLSXGen::fromArray($excel_rows)->downloadAs($fl_name);
		exit();
	}
}
?>