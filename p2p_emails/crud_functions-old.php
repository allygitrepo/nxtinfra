<?php

include("../dbcon.php");
include("../baseurl.php");


if(!empty($_POST['oprName']))
{
    if($_POST['oprName'] == 'delete_email')
    {
        delete_email($con, $_POST['id']);
    }
    
    if($_POST['oprName'] == 'delete_multiple_email')
    {
        delete_multiple_email($con, $_POST['ids']);
    }
    
    if($_POST['oprName'] == 'create_grn')
    {
        create_grn($con, $_POST['id'], $_POST['user'], $_POST['userId'], $_POST['type'], $_POST['baseUrl']);
    }
}
else
{
    echo 'Operation not defined!';
}










/**
* Function to delete email by ID
* 
* @param mixed $id
*/
function delete_email($con, $id)
{
    if(!empty($id))
    {
        $sqlDelete = "UPDATE email_inbox SET deleted=1 WHERE id = " . $id;
        $query1 = mysqli_query($con, $sqlDelete);
        $mysqlError = mysqli_error($con);
        
        if(empty($mysqlError))
        {
            echo 'Success';
        }
        else
        {
            echo $mysqlError;
        }
    }
    else
    {
        echo 'Invalid input!';
    }
}

/**
* Function to delete email by ID
* 
* @param mixed $id
*/
function delete_multiple_email($con, $ids)
{
    if(!empty($ids))
    {
        $ids = str_replace('"', '', $ids);
        $ids = str_replace('[', '', $ids);
        $ids = str_replace(']', '', $ids);
        $sqlDelete = "UPDATE email_inbox SET deleted=1 WHERE id IN ( " . $ids . " )";
        $query1 = mysqli_query($con, $sqlDelete);
        $mysqlError = mysqli_error($con);
        
        if(empty($mysqlError))
        {
            echo 'Success';
        }
        else
        {
            echo $mysqlError;
        }
    }
    else
    {
        echo 'Invalid input!';
    }
    
}

/**
* Function to Create GRN from EMail
* 
* @param mixed $id
*/
function create_grn($con, $id, $user, $userId, $type, $baseURL)
{
    if(!empty($id))
    {
        // Get Email record
        $emailSQL = "SELECT * FROM email_inbox WHERE id =" . $id;
        $resultEmail  = mysqli_query($con, $emailSQL);
        $mysqlError = mysqli_error($con);
        $email = mysqli_fetch_array($resultEmail);
        
        // Get Supplier ID
        $supplierId = 0;
        $supplierSQL = "SELECT * FROM sma_party_mst WHERE party_email = '" . $email['from_email'] . "'";        
        $result = mysqli_query($con, $supplierSQL);
        $mysqlError = mysqli_error($con);
        $supplier = mysqli_fetch_array($result);
        $supplierId = (!empty($supplier) ? $supplier['id'] : 0);
        
        $status = 'Synced';
            
        if($type == 'grn')
        {
            // GET LAST serial number from GRN
            $serialSQL = "SELECT max(id) as srno from sma_supplier_invoice ";
            $res  = mysqli_query($con, $serialSQL);
            $mysqlError = mysqli_error($con);
            $lastRecord = mysqli_fetch_array($res);
            
            $serialNo = $lastRecord['srno'] + 1;
            $invoice_date = date('Y-m-d');
            $created_date = date('Y-m-d');
            
            // GET PO ref no ID from DB
            //$poSQL = "SELECT po_number from sma_purchase_order WHERE po_number = '" . $email['subject'] . "'";
			$poSQL = "SELECT * from sma_purchase_order WHERE po_number = '" . $email['subject'] . "'";
            $resultPO  = mysqli_query($con, $poSQL);
            $mysqlError = mysqli_error($con);
            $poRecord = mysqli_fetch_array($resultPO);        
            //var_dump($poRecord);
            $our_po_ref_no = (!empty($poRecord) ? $poRecord['id'] : '');            
            
            //$user       = $_SESSION['user'];
            //$userid     = $_SESSION['usrid'];
            
            // Create GRN Record
            /*$insertSQL = "INSERT INTO sma_supplier_invoice (id, supplier_invoice_no, company_id, invoice_date, created_date, our_po_ref_no, our_pr_no, 
                        delivery_challen_no, delivery_date, delivery_mode, suplier_name, transport_lr_no, lr_date, transporter_name, credit_days, 
                        due_date, state, retention_flag, grn_status , grndraft_by, grndraft_date, supplier_gst_no, supplier_location, bill_no, 
                        department,  invoice_type, trans_type, gst_flag , location, prev_date_flag, prev_date_flag_creatby, prev_date_flag_date, 
                        prev_date_flag_remark, invoice_received_date)
                        VALUES('$serialNo', '', '', '$invoice_date', '$created_date', '$our_po_ref_no', '$our_pr_no', 
                            '$delivery_challen_no', '$delivery_date', '$delivery_mode', '$suplier_name', '$transport_lr_no', '$lr_date', '$transporter_name', 
                            '$credit_days', '$due_date', '$state', '$retention_flag', '$status' , '$user', now(), '$supplier_gst_no', '$supplier_location', 
                            '$bill_no', '$department',  '$invoice_type', '$trans_type', '$gst_flag', '$location_id' , '$prev_date_flag', '$userid', now(), 
                            '$prev_date_flag_remark', '$invoice_received_date' )";*/
                            
            $insertSQL = "INSERT INTO sma_supplier_invoice (id, supplier_invoice_no, company_id, invoice_date, created_date, our_po_ref_no, our_pr_no, 
                        delivery_challen_no, delivery_date, delivery_mode, suplier_name, transport_lr_no, lr_date, transporter_name, credit_days, 
                        due_date, state, retention_flag, grn_status , grndraft_by, grndraft_date, supplier_gst_no, supplier_location, bill_no, 
                        department,  invoice_type, trans_type, gst_flag , location, prev_date_flag, prev_date_flag_creatby, prev_date_flag_date, 
                        prev_date_flag_remark, invoice_received_date)
                        VALUES('$serialNo', '', '', '', '$created_date', '$our_po_ref_no', '', 
                            '', '', '', '$supplierId', '', '', '', 
                            '', '', '', '', '$status' , '$user', now(), '', '', 
                            '', '',  '', '', '', '' , '', '$userId', now(), 
                            '', '$invoice_date' )";
           
            $query = mysqli_query($con, $insertSQL);
            $mysqlError= mysqli_error($con);
            
            
            // Add GRN number to email record
            $sqlUpdate = "UPDATE email_inbox SET grn_no=" . $serialNo . ", grn_type='grn' WHERE id = " . $id;
            $query1 = mysqli_query($con, $sqlUpdate);
            $mysqlError = mysqli_error($con);
            
            // Move/Copy all attachments to GRN folder
            $appPath = str_replace('p2p_emails', '', dirname(getcwd()));
            $attachmentFolder = 'attachments/' . date('d-M-Y', strtotime($email['message_date'])) . '/' . $email['message_id'];
            $attachments = glob($attachmentFolder . "/*.*");
            //print_r($attachments);
            
            $destinationRoot = $appPath . "/supp_invoice/uploads/si/" . $serialNo;        
            //var_dump($destinationRoot);
            
            if(!empty($attachments) && count($attachments) > 0)
            {
                // Check to see if destination folder exists
                if(!file_exists($destinationRoot)){
                    mkdir($destinationRoot, 0777, true);
                }
                
                foreach($attachments as $fileName)
                {
                    if(!strrpos($fileName, '.png'))
                    {
                        $fileNameParts = explode('/', $fileName);
                        $destinationFile = $destinationRoot . '/' . $fileNameParts[count($fileNameParts)-1];
                        $destinationPath = "uploads/si/" . $serialNo;
                        
                        $insertFileSQL = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, share_point_link, reference_id, date_uploaded) 
                                VALUES( 'SI', '" . $fileNameParts[count($fileNameParts)-1] . "', '$destinationPath', '13', 'Email attachment', '', '$serialNo', now() )";
                        if (mysqli_query($con, $insertFileSQL))
                        {                    
                            //rename($fileName, $destinationFile);    // Move files to new folder                    
                            copy($fileName, $destinationFile);  // Copy files to new folder
                        }
                        else 
                        {
                            echo "Error: " . mysqli_error($con);
                        }
                    }                
                    
                }
                
                // Delete folder
                //rmdir($attachmentFolder);
            }            
            
        }
        else if($type == 'opex')
        {
            // GET LAST serial number from OPEX company expense table
            $serialSQL = "SELECT max(id) as srno from sma_travel_expenses ";
            $res  = mysqli_query($con, $serialSQL);
            $mysqlError = mysqli_error($con);
            $lastRecord = mysqli_fetch_array($res);
            
            $serialNo = $lastRecord['srno'] + 1;
            $exp_type = 'C';
            $created_date = date('Y-m-d');
            $approval_ref_no = $serialNo;
            //$supplierId = ($supplierId == 0) ? '' : $supplierId;
            
            //$sql="Insert into sma_travel_expenses ( id, exp_type, emp_id, company_id, dated, approval_ref_no, status, draft_by, draft_dated , total_amount, approval_number, trans_type, location ) values ( '$ce_id', '$exp_type', '$emp_id', '$company_id', '$datedd', '$approval_ref_no',  'Draft', '$user', now(), '$total_amount', '$approval_number', '$trans_type', '$location' ) ";
            
            $insertSQL = "INSERT INTO sma_travel_expenses (id, exp_type, emp_id, company_id, dated, invoice_received_date, approval_ref_no, status, draft_by, draft_dated, 
                                total_amount, approval_number, trans_type, location ) 
                                VALUES ('$serialNo', '$exp_type', '$supplierId', '0', '$created_date', '$created_date', '$approval_ref_no', '$status', '$user', now(), 
                                '', '', '', '' ) ";           
            $query = mysqli_query($con, $insertSQL);
            $mysqlError= mysqli_error($con);
            
            
            // Add GRN number to email record
            $sqlUpdate = "UPDATE email_inbox SET grn_no=" . $serialNo . ", grn_type='opex' WHERE id = " . $id;
            $query1 = mysqli_query($con, $sqlUpdate);
            $mysqlError = mysqli_error($con);
            
            // Move/Copy all attachments to GRN folder
            $appPath = str_replace('p2p_emails', '', dirname(getcwd()));
            $attachmentFolder = 'attachments/' . date('d-M-Y', strtotime($email['message_date'])) . '/' . $email['message_id'];
            $attachments = glob($attachmentFolder . "/*.*");
            //print_r($attachments);
            
            $destinationRoot = $appPath . "/travel_approval/uploads/ce/" . $serialNo;        
            //var_dump($destinationRoot);
            
            if(!empty($attachments) && count($attachments) > 0)
            {
                // Check to see if destination folder exists
                if(!file_exists($destinationRoot)){
                    mkdir($destinationRoot, 0777, true);
                }
                
                foreach($attachments as $fileName)
                {
                    if(!strrpos($fileName, '.png'))
                    {
                        $fileNameParts = explode('/', $fileName);
                        $destinationFile = $destinationRoot . '/' . $fileNameParts[count($fileNameParts)-1];
                        $destinationPath = "uploads/ce/" . $serialNo;
                        
                        $insertFileSQL = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, share_point_link, reference_id, date_uploaded) 
                                VALUES( 'CE', '" . $fileNameParts[count($fileNameParts)-1] . "', '$destinationPath', '13', 'Email attachment', '', '$serialNo', now() )";
                        if (mysqli_query($con, $insertFileSQL))
                        {                    
                            //rename($fileName, $destinationFile);    // Move files to new folder                    
                            copy($fileName, $destinationFile);  // Copy files to new folder
                        }
                        else 
                        {
                            echo "Error: " . mysqli_error($con);
                        }
                    }                
                    
                }
                
                // Delete folder
                //rmdir($attachmentFolder);
            }
        }
        
        if(empty($mysqlError))
        {
            echo 'Success:' . $type . ':' . $serialNo;
        }
        else
        {
            echo $mysqlError;
        }
    }
    else
    {
        echo 'Invalid input!';
    }
}



?>