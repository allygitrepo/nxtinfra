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
	$company_id    = $_POST['company_id'] ?? '';
	$gtype         = $_GET['gtype'] ?? 'C';

	$from_date = (!empty($from_date_raw) && $from_date_raw != '1970-01-01' && $from_date_raw != '0000-00-00') ? date('Y-m-d', strtotime($from_date_raw)) : '';
	$to_date   = (!empty($to_date_raw) && $to_date_raw != '1970-01-01' && $to_date_raw != '0000-00-00') ? date('Y-m-d', strtotime($to_date_raw)) : '';

	$from_date_dmy = (!empty($from_date_raw) && $from_date_raw != '1970-01-01' && $from_date_raw != '0000-00-00') ? date('d-m-Y', strtotime($from_date_raw)) : '';
	$to_date_dmy   = (!empty($to_date_raw) && $to_date_raw != '1970-01-01' && $to_date_raw != '0000-00-00') ? date('d-m-Y', strtotime($to_date_raw)) : '';

	$d_type = 'Operating';
	if ($gtype == 'R') {
		$d_type = 'Regular';
	} else if ($gtype == 'T') {
		$d_type = 'Travel';
	}

	if (!empty($from_date_dmy) && !empty($to_date_dmy)) {
		$title_banner = $d_type . ' Expense Summary Register from ' . $from_date_dmy . ' TO ' . $to_date_dmy;
	} else if (!empty($from_date_dmy)) {
		$title_banner = $d_type . ' Expense Summary Register from ' . $from_date_dmy;
	} else if (!empty($to_date_dmy)) {
		$title_banner = $d_type . ' Expense Summary Register up to ' . $to_date_dmy;
	} else {
		$title_banner = $d_type . ' Expense Summary Register';
	}

	$excel_rows = [];
	$excel_rows[] = [$title_banner];
	$excel_rows[] = [
		'Sr.No.',
		'Expense No.',
		'Supplier Name',
		'PAN',
		'GST',
		'MSME',
		'Company',
		'Tally Status',
		'Invoice Date',
		'Invoice Received Date',
		'NOA Ref. No.',
		'Invoice No.',
		'Material Name',
		'Description',
		'Account Year',
		'Budget Group',
		'Budget Sub Group',
		'Budget Code',
		'Qty.',
		'Taxable Amount',
		'GST Amount',
		'Total Amount',
		'Payment Status',
		'Approved By',
		'Workflow Type',
		'Status',
		'Decision'
	];

	if (!empty($_SESSION['sqlex'])) {
		$sql = $_SESSION['sqlex'];
	} else if (!empty($_SESSION['sqlreg'])) {
		$sql = $_SESSION['sqlreg'];
	} else {
		$sql = "SELECT * FROM sma_travel_expenses WHERE exp_type = '$gtype' AND del != 'Y'";
		if (!empty($from_date) && !empty($to_date)) {
			$sql .= " AND dated >= '$from_date' AND dated <= '$to_date'";
		}
		if (!empty($company_id)) {
			$sql .= " AND company_id = '$company_id'";
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
		$del = $row['del'] ?? '';
		if ($del == 'Y') {
			continue;
		}

		$idd              = $row['id'] ?? '';
		$emp_id           = $row['emp_id'] ?? '';
		$onbehalf_emp_id  = $row['onbehalf_emp_id'] ?? '';
		$gtype_row        = $row['exp_type'] ?? $gtype;
		$company_id_row   = $row['company_id'] ?? '';
		$trans_type       = $row['trans_type'] ?? '';
		$dated_raw        = $row['dated'] ?? '';
		$inv_rec_date_raw = $row['invoice_received_date'] ?? '';
		$approval_ref_no  = !empty($row['approval_number']) ? $row['approval_number'] : ($row['approval_ref_no'] ?? '');
		$status           = $row['status'] ?? '';
		$approval_status  = $row['approval_status'] ?? '';
		$tally_status_raw = $row['tally_status'] ?? '';
		$paid_status      = $row['paid_status'] ?? '';
		$hdr_budget_id    = $row['budget_id'] ?? 0;

		$tally_status = 'Unticked';
		if ($tally_status_raw == 'R') {
			$tally_status = 'Ready for Tally Upload';
		} else if ($tally_status_raw == 'U') {
			$tally_status = 'Tally Uploaded';
		}

		$invoice_date          = (!empty($dated_raw) && $dated_raw != '1970-01-01' && $dated_raw != '0000-00-00') ? date('d-m-Y', strtotime($dated_raw)) : '';
		$invoice_received_date = (!empty($inv_rec_date_raw) && $inv_rec_date_raw != '1970-01-01' && $inv_rec_date_raw != '0000-00-00') ? date('d-m-Y', strtotime($inv_rec_date_raw)) : '';

		// Supplier / Employee Name & Master details
		$emp_name          = '';
		$party_pan_number  = '';
		$party_gst_number  = '';
		$party_msme_number = '';
		if ($gtype_row == 'C') {
			if (!empty($emp_id)) {
				$sql_sup = "SELECT party_name, party_pan_number, party_gst_number, party_msme_number FROM `sma_party_mst` WHERE id = '$emp_id'";
				$res_sup = mysqli_query($con, $sql_sup);
				if ($res_sup && $r_sup = mysqli_fetch_array($res_sup)) {
					$emp_name          = $r_sup['party_name'] ?? '';
					$party_pan_number  = $r_sup['party_pan_number'] ?? '';
					$party_gst_number  = $r_sup['party_gst_number'] ?? '';
					$party_msme_number = $r_sup['party_msme_number'] ?? '';
				}
			}
		} else {
			$uid = !empty($onbehalf_emp_id) ? $onbehalf_emp_id : $emp_id;
			if (!empty($uid)) {
				$sql_u = "SELECT username FROM `sma_user` WHERE id = '$uid'";
				$res_u = mysqli_query($con, $sql_u);
				if ($res_u && $r_u = mysqli_fetch_array($res_u)) {
					$emp_name = $r_u['username'] ?? '';
				}
			}
		}

		// Company lookup
		$comp_name = '';
		if (!empty($company_id_row)) {
			$sql_c = "SELECT comp_code, comp_name FROM `company` WHERE comp_id = '$company_id_row'";
			$res_c = mysqli_query($con, $sql_c);
			if ($res_c && $r_c = mysqli_fetch_array($res_c)) {
				$comp_name = !empty($r_c['comp_code']) ? $r_c['comp_code'] : ($r_c['comp_name'] ?? '');
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

		// Approver lookup
		$doc_type = ($gtype_row == 'C') ? 'CE' : (($gtype_row == 'R') ? 'RE' : 'TE');
		$approver_name = '';
		if (!empty($idd)) {
			$sql_ap = "SELECT create_by FROM `workflow_history` WHERE doc_type = '$doc_type' AND doc_id = '$idd' AND status IN ('Approved', 'Submitted') ORDER BY id DESC LIMIT 1";
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

		// Payment status lookup
		$st_flag = ($gtype_row == 'C') ? 'C' : 'T';
		$payment_status_display = !empty($paid_status) ? $paid_status : 'Unpaid';
		if (!empty($idd)) {
			$sql_pay = "SELECT a.utr_no, a.paid_date FROM `payment_header` a LEFT JOIN `payment_details` b ON a.id = b.payment_hdr_id WHERE a.st_flag = '$st_flag' AND b.supp_id = '$idd' AND a.del != 'Y' LIMIT 1";
			$res_pay = mysqli_query($con, $sql_pay);
			if ($res_pay && $r_pay = mysqli_fetch_array($res_pay)) {
				if (!empty($r_pay['utr_no'])) {
					$payment_status_display = 'Paid';
				}
			}
		}

		// Child expense details
		$sql_dtl = "SELECT * FROM `sma_expenses` WHERE approval_ref_no = '$idd' AND exp_type = '$gtype_row'";
		$res_dtl = mysqli_query($con, $sql_dtl);
		$has_dtl = false;

		while ($rw = mysqli_fetch_array($res_dtl)) {
			$has_dtl = true;
			$ln++;
			$reference_id   = $rw['reference'] ?? '';
			$invoice_no     = $rw['invoice_no'] ?? '';
			$qty            = floatval($rw['quantity'] ?? 1);
			$amount         = floatval($rw['amount'] ?? 0);
			$gst_amount     = floatval($rw['gst_amount'] ?? 0);
			$note           = $rw['note'] ?? '';
			$account_year_r = $rw['account_year'] ?? '';
			$budget_id      = !empty($rw['budget_id']) ? $rw['budget_id'] : $hdr_budget_id;
			$budget_name_id = $rw['budget_name'] ?? '';
			$budget_head_id = $rw['budget_head'] ?? '';

			$account_year_map = [
				'1' => '2017-2018', '2' => '2018-2019', '3' => '2019-2020',
				'4' => '2020-2021', '5' => '2021-2022', '6' => '2022-2023',
				'7' => '2023-2024', '8' => '2024-2025', '9' => '2025-2026', '10' => '2026-2027'
			];
			$account_year = $account_year_map[$account_year_r] ?? $account_year_r;

			$total_amt = round($amount + $gst_amount, 2);

			// Material / Product lookup
			$product_name = $reference_id;
			$prod_b_head  = '';
			$prod_b_name  = '';
			if (!empty($reference_id) && is_numeric($reference_id)) {
				$sql_mat = "SELECT name, budget_head, budget_name FROM `sma_product` WHERE id = '$reference_id'";
				$res_mat = mysqli_query($con, $sql_mat);
				if ($res_mat && $r_mat = mysqli_fetch_array($res_mat)) {
					$product_name = $r_mat['name'] ?? $reference_id;
					$prod_b_head  = $r_mat['budget_head'] ?? '';
					$prod_b_name  = $r_mat['budget_name'] ?? '';
				}
			}

			// Budget Group & Sub Group Multi-Tier Lookup
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

			// 3. Direct budget_head / budget_name values in child table
			if (empty($budget_head) && !empty($budget_head_id)) {
				if (is_numeric($budget_head_id)) {
					$sql_sub = "SELECT budget_head, budget_name FROM `sma_budget_subgroup` WHERE id = '$budget_head_id'";
					$res_sub = mysqli_query($con, $sql_sub);
					if ($res_sub && $r_sub = mysqli_fetch_array($res_sub)) {
						$budget_head = $r_sub['budget_head'] ?? '';
						if (empty($budget_name) && !empty($r_sub['budget_name'])) {
							$budget_name_id = $r_sub['budget_name'];
						}
					}
				} else {
					$budget_head = $budget_head_id;
				}
			}

			if (empty($budget_name) && !empty($budget_name_id)) {
				if (is_numeric($budget_name_id)) {
					$sql_bn = "SELECT name FROM `sma_budget_name` WHERE id = '$budget_name_id'";
					$res_bn = mysqli_query($con, $sql_bn);
					if ($res_bn && $r_bn = mysqli_fetch_array($res_bn)) {
						$budget_name = $r_bn['name'] ?? '';
					}
				} else {
					$budget_name = $budget_name_id;
				}
			}

			$excel_rows[] = [
				$ln,
				$idd,
				$emp_name,
				$party_pan_number,
				$party_gst_number,
				$party_msme_number,
				$comp_name,
				$tally_status,
				$invoice_date,
				$invoice_received_date,
				$approval_ref_no,
				$invoice_no,
				$product_name,
				$note,
				$account_year,
				$budget_name,
				$budget_head,
				$budget_code,
				$qty,
				$amount,
				$gst_amount,
				$total_amt,
				$payment_status_display,
				$approver_name,
				$workflow_type,
				$status,
				$approval_status
			];
		}

		if (!$has_dtl) {
			$ln++;
			$total_amt = floatval($row['total_amount'] ?? 0);
			$excel_rows[] = [
				$ln,
				$idd,
				$emp_name,
				$party_pan_number,
				$party_gst_number,
				$party_msme_number,
				$comp_name,
				$tally_status,
				$invoice_date,
				$invoice_received_date,
				$approval_ref_no,
				'',
				'',
				$row['remarks'] ?? '',
				'',
				'',
				'',
				'',
				1,
				$total_amt,
				0,
				$total_amt,
				$payment_status_display,
				$approver_name,
				$workflow_type,
				$status,
				$approval_status
			];
		}
	}

	if ($prn == 'excel') {
		$fl_name = $d_type . '_Expense_Summary_Report_' . date('Y-m-d') . '.xlsx';
		\Shuchkin\SimpleXLSXGen::fromArray($excel_rows)->downloadAs($fl_name);
		exit();
	}
}
?>