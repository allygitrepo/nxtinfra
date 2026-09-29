<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function render_error_page($title, $message, $show_login_btn = false) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo htmlspecialchars($title); ?> - NXT P2P</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <style>
            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
            body {
                min-height: 100vh;
                font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
                background: #0d1b2a;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1.5rem;
                position: relative;
                overflow: hidden;
            }
            body::before {
                content: '';
                position: fixed;
                inset: 0;
                background:
                    radial-gradient(ellipse 80% 60% at 20% 20%, rgba(30, 90, 160, 0.18) 0%, transparent 60%),
                    radial-gradient(ellipse 60% 50% at 80% 80%, rgba(255, 140, 0, 0.10) 0%, transparent 55%);
                pointer-events: none;
            }
            body::after {
                content: '';
                position: fixed;
                inset: 0;
                background-image:
                    linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
                background-size: 60px 60px;
                pointer-events: none;
            }
            .error-wrapper {
                width: 100%;
                max-width: 480px;
                background: #ffffff;
                border-radius: 20px;
                padding: 2.5rem;
                box-shadow: 0 32px 80px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.06);
                position: relative;
                z-index: 1;
                text-align: center;
            }
            .brand-logo {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 14px;
                margin-bottom: 2rem;
            }
            .brand-logo-icon {
                width: 48px; height: 48px;
                background: linear-gradient(135deg, #ff8c00, #e55317);
                border-radius: 12px;
                display: flex; align-items: center; justify-content: center;
                font-size: 22px;
                color: #fff;
                box-shadow: 0 4px 16px rgba(255,140,0,0.3);
            }
            .brand-logo-text strong {
                color: #0d1b2a;
                font-size: 24px;
                font-weight: 700;
                letter-spacing: -0.3px;
            }
            .error-icon {
                font-size: 56px;
                color: #e53e3e;
                margin-bottom: 1.5rem;
            }
            h2 {
                color: #0d1b2a;
                font-size: 22px;
                font-weight: 700;
                margin-bottom: 0.75rem;
            }
            p {
                color: #5a6a7a;
                font-size: 14.5px;
                line-height: 1.6;
                margin-bottom: 2rem;
            }
            .btn-login {
                display: inline-block;
                width: 100%;
                padding: 12px;
                background: linear-gradient(135deg, #ff8c00, #e55317);
                color: #fff;
                border: none;
                border-radius: 10px;
                font-size: 15px;
                font-weight: 700;
                text-decoration: none;
                transition: opacity 0.18s, transform 0.12s, box-shadow 0.18s;
                box-shadow: 0 4px 16px rgba(229,83,23,0.30);
            }
            .btn-login:hover {
                opacity: 0.93;
                box-shadow: 0 6px 20px rgba(229,83,23,0.38);
            }
            .btn-login:active {
                transform: scale(0.98);
            }
        </style>
    </head>
    <body>
    <div class="error-wrapper">
        <div class="brand-logo">
            <div class="brand-logo-icon">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div class="brand-logo-text">
                <strong>NXT - P2P</strong>
            </div>
        </div>
        <div class="error-icon">
            <i class="fa-solid fa-circle-exclamation"></i>
        </div>
        <h2><?php echo htmlspecialchars($title); ?></h2>
        <p><?php echo htmlspecialchars($message); ?></p>
        <?php if ($show_login_btn) { ?>
            <a href="index.php" class="btn-login">Go to Login</a>
        <?php } ?>
    </div>
    </body>
    </html>
    <?php
    exit;
}

// 1. Check if user is logged in
if (!isset($_SESSION['user'])) {
    http_response_code(403);
    render_error_page("Access Denied", "You must be logged in to view this document. Please log in first to continue.", true);
}

// 2. Check if ID is provided
if (!isset($_GET['id'])) {
    http_response_code(400);
    render_error_page("Invalid Request", "Document ID is required to fetch the document.");
}

include("dbcon.php");

function decrypt_id($safe_b64) {
    $key = 'NXT_P2P_Secret_Key_2026';
    $method = 'aes-128-cbc';
    $iv_length = openssl_cipher_iv_length($method);
    $iv = substr(hash('sha256', $key), 0, $iv_length);
    $b64 = str_replace(array('-', '_'), array('+', '/'), $safe_b64);
    $remainder = strlen($b64) % 4;
    if ($remainder) {
        $b64 .= str_repeat('=', 4 - $remainder);
    }
    $decrypted = openssl_decrypt(base64_decode($b64), $method, $key, 0, $iv);
    return $decrypted;
}

function check_file_access($con, $row){
    if (!isset($_SESSION['user'])){
        return false;
    }
    $username = $_SESSION['user'];
    $usrid = $_SESSION['usrid'];
    $primary_role = isset($_SESSION['primary_role']) ? $_SESSION['primary_role'] : '';
    $comid = isset($_SESSION['comid']) ? $_SESSION['comid'] : '';

    if ($username === 'Admin' || in_array($primary_role, array('COO', 'Director'))) {
        return true;
    }

    $uploaded_by = isset($row['uploaded_by']) ? $row['uploaded_by'] : null;
    if ($uploaded_by !== null && intval($uploaded_by) === intval($usrid)) {
        return true;
    }

    $module = $row['module'];
    $ref_id = mysqli_real_escape_string($con, $row['reference_id']);

    if ($module === 'PR') {
        $sql = "SELECT * FROM sma_purchase_req WHERE id = '$ref_id' LIMIT 1";
        $res = mysqli_query($con, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            $pr = mysqli_fetch_assoc($res);
            if ($pr['draft_by'] === $username || intval($pr['created_by']) === intval($usrid)) {
                return true;
            }
            $approvers = array(
                $pr['approver_1'], $pr['approver_2'], $pr['approver_3'], $pr['approver_4'],
                $pr['approver_5'], $pr['approver_6'], $pr['approver_7'], $pr['approver_8'],
                $pr['approver_9'], $pr['approver_10'], $pr['current_approver']
            );
            if (in_array($usrid, $approvers)) {
                return true;
            }
            if (!empty($comid)) {
                $allowed = explode(',', $comid);
                if (in_array($pr['company_id'], $allowed)) {
                    return true;
                }
            }
        }
        return false;
    }

    if ($module === 'PO') {
        $sql = "SELECT * FROM sma_purchase_order WHERE id = '$ref_id' LIMIT 1";
        $res = mysqli_query($con, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            $po = mysqli_fetch_assoc($res);
            if ($po['draft_by'] === $username || intval($po['created_by']) === intval($usrid)) {
                return true;
            }
            if (!empty($comid)) {
                $allowed = explode(',', $comid);
                if (in_array($po['project'], $allowed)) {
                    return true;
                }
            }
        }
        return false;
    }

    if ($module === 'VN') {
        $sql = "SELECT * FROM sma_party_mst WHERE id = '$ref_id' LIMIT 1";
        $res = mysqli_query($con, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            $vn = mysqli_fetch_assoc($res);
            if (intval($vn['created_by']) === intval($usrid)) {
                return true;
            }
            if (!empty($comid)) {
                $allowed = explode(',', $comid);
                if (in_array($vn['company_id'], $allowed)) {
                    return true;
                }
            }
        }
        return false;
    }

    if (in_array($module, array('CE', 'TE', 'RE', 'DE'))) {
		
        $sql = "SELECT * FROM sma_travel_expenses WHERE id = '$ref_id' LIMIT 1";
        $res = mysqli_query($con, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            $ta = mysqli_fetch_assoc($res);
            if (intval($ta['current_approver']) === intval($usrid) || intval($ta['created_by']) === intval($usrid) || intval($ta['emp_id']) === intval($usrid)) {
                return true;
            }
            if (!empty($comid)) {
                $allowed = explode(',', $comid);
                if (in_array($ta['company_id'], $allowed)) {
                    return true;
                }
            }
        }
        return false;
    }
	if (in_array($module, array( 'TA'))) {
        $sql = "SELECT * FROM sma_traval_approval WHERE id = '$ref_id' LIMIT 1";
        $res = mysqli_query($con, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            $ta = mysqli_fetch_assoc($res);
            if (intval($ta['created_by']) === intval($usrid) || intval($ta['emp_id']) === intval($usrid)) {
                return true;
            }
            if (!empty($comid)) {
                $allowed = explode(',', $comid);
                if (in_array($ta['company_id'], $allowed)) {
                    return true;
                }
            }
        }
        return false;
    }
	
	if ($module === 'AV') {
        $sql = "SELECT * FROM sma_advance WHERE id = '$ref_id' LIMIT 1";
        $res = mysqli_query($con, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            $tn = mysqli_fetch_assoc($res);
            if (intval($tn['created_by']) === intval($usrid)) {
                return true;
            }
            if (!empty($comid)) {
                $allowed = explode(',', $comid);
                if (in_array($tn['company_id'], $allowed)) {
                    return true;
                }
            }
        }
        return false;
    }
	
    if ($module === 'TN') {
        $sql = "SELECT * FROM sma_tender_header WHERE id = '$ref_id' LIMIT 1";
        $res = mysqli_query($con, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            $tn = mysqli_fetch_assoc($res);
            if (intval($tn['created_by']) === intval($usrid)) {
                return true;
            }
            if (!empty($comid)) {
                $allowed = explode(',', $comid);
                if (in_array($tn['company_id'], $allowed)) {
                    return true;
                }
            }
        }
        return false;
    }

    if ($module === 'SI') {
        $sql = "SELECT * FROM sma_supplier_invoice WHERE id = '$ref_id' LIMIT 1";
        $res = mysqli_query($con, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            $si = mysqli_fetch_assoc($res);
            if (intval($si['created_by']) === intval($usrid)) {
                return true;
            }
            if (!empty($comid)) {
                $allowed = explode(',', $comid);
                if (in_array($si['company_id'], $allowed)) {
                    return true;
                }
            }
        }
        return false;
    }

    return true;
}

$doc_id_input = $_GET['id'];
$doc_id = null;

$decrypted = decrypt_id($doc_id_input);
if ($decrypted !== false && is_numeric($decrypted)) {
    $doc_id = intval($decrypted);
} elseif (is_numeric($doc_id_input)) {
    $doc_id = intval($doc_id_input);
}

if ($doc_id === null || $doc_id <= 0) {
    http_response_code(400);
    render_error_page("Invalid Request", "Document ID is invalid or could not be decrypted.");
}

$doc_id = mysqli_real_escape_string($con, $doc_id);
$sql = "SELECT * FROM file_uploads WHERE id = '$doc_id' LIMIT 1";
$result = mysqli_query($con, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    http_response_code(404);
    render_error_page("Document Not Found", "The requested document could not be found in the system database.");
}

$row = mysqli_fetch_assoc($result);

// Perform authorization check
 if (!check_file_access($con, $row)) {
    http_response_code(403);
    render_error_page("Access Denied", "You are not authorized to view this document.");
} 

$file_name = $row['file_name'];
$file_path = $row['file_path']; // e.g. "uploads/pr/1385"
$module = $row['module']; // e.g. "PR"

$base_dir = dirname(__FILE__) . '/';

// Map module code to the directory name
$module_dir = '';
if ($module === 'PR') {
    $module_dir = 'purchase_requisition/';
} else if ($module === 'VN') {
    $module_dir = 'vendor/';
} else if (in_array($module, array('TE', 'TA', 'CE', 'RE', 'DE'))) {
    $module_dir = 'travel_approval/';
} else if ($module === 'TN') {
    $module_dir = 'tender/';
} else if ($module === 'SI') {
    $module_dir = 'supp_invoice/';
} else if ($module === 'PO') {
    $module_dir = 'purchase_order/';
} else if ($module === 'GR') {
    $module_dir = 'grn/';
} else if ($module === 'AV') {
    $module_dir = 'advance/';
} else if ($module === 'PY') {
    $module_dir = 'payment/';
} else if ($module === 'AP') {
    $module_dir = 'approval/';
}

// Build the full server file path
$full_path = $base_dir . $module_dir . $file_path . '/' . $file_name;

// Normalize path separators
$full_path = str_replace(array('//', '\\\\', '\\'), array('/', '/', '/'), $full_path);

if (!file_exists($full_path)) {
    http_response_code(404);
    render_error_page("File Not Found", "The requested document file does not exist on the server.". $full_path);
}

// Detect MIME type
$mime_type = mime_content_type($full_path);
if (!$mime_type) {
    $mime_type = "application/octet-stream";
}

// Disable browser caching for security
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Set headers and stream file
header("Content-Type: " . $mime_type);
header("Content-Length: " . filesize($full_path));

if (isset($_GET['download'])) {
    header("Content-Disposition: attachment; filename=\"" . basename($file_name) . "\"");
} else {
    header("Content-Disposition: inline; filename=\"" . basename($file_name) . "\"");
}

readfile($full_path);
exit;
?>
