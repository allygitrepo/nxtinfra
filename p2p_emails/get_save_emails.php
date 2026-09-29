<?php

require_once __DIR__ . '/google-connect.php';
require_once __DIR__ . '/functions.php';

include("../dbcon.php");
include("../baseurl.php");

if(empty($_SESSION)) 
{
    session_start();     
}

// GET last email date from DB
$sqlLastEmail = "SELECT * FROM email_inbox WHERE deleted=0 ORDER BY message_date DESC LIMIT 0, 1";
$query = mysqli_query($con, $sqlLastEmail);
$lastEmail = mysqli_fetch_array($query);

// Get the API client and construct the service object.
$service = new Google_Service_Gmail($client);
$userId = 'me';
//var search_query = "after:"+ld+" before:"+cd;
$searchQuery = 'has:attachment';
//$searchQuery = '';
if(!empty($lastEmail))
{
    $searchQuery .= ' after:' . strtotime($lastEmail['message_date'] . '+1 second');
}
else
{
    $lastEmail = array();
    $lastEmail['message_id'] = '0';
    $lastEmail['message_date'] = '1-1-1970';
}

$messages = listMessages($service, $userId, [
    #'maxResults' => 20, // Return 20 messages.
    'labelIds' => 'INBOX', // Return messages in inbox.
    'q' => $searchQuery,  // Filter query
]);


// Save emails to DB
$cntr = 0;
$saved = false;
$error = '';
foreach ($messages as $row) 
{     
    $message = getMessage($service, $userId, $row->getId());    
    $headerArr = getHeaderArr($message->getPayload()->getHeaders());

    $messageId = $row->getId();
    $folderDate = date('d-M-Y', strtotime($headerArr['Date']));
    
    if($messageId != $lastEmail['message_id'] && strtotime($headerArr['Date']) > strtotime($lastEmail['message_date']))
    {
        //$messageDate = (empty($headerArr['Date']) ? date('d-M-Y H:i:s') : date('d-M-Y H:i:s', strtotime($headerArr['Date'])));
        $messageDate = (empty($headerArr['Date']) ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', strtotime($headerArr['Date'])));
        $from = (empty($headerArr['From']) ? '': $headerArr['From']);
        $subject = (empty($headerArr['Subject']) ? '': $headerArr['Subject']);
        
        $splitFromAddress = explode('<', $from, 2);
        $fromAddress = str_replace('>', '', $splitFromAddress[1]);

        $emailMessage = parse_email($message->getPayload()->getParts());
        $link = ""; $html = "";
        foreach($emailMessage as $msg)
        {
            if($msg["type"] == "html")
            {
                $html = $msg["body"];
            }
            else if($msg["type"] == "text")
            {
                //you can do anything with text part of email
            }
            else
            {
                // WRITE ATTACHMENTS TO FILE                
                if (!file_exists("attachments")) {
                    mkdir("attachments");
                }
                
                if (!file_exists("attachments/" . $folderDate)) {
                    mkdir("attachments/" . $folderDate);
                }
                
                $attachmentId = $msg["body"];
                $name = $msg["name"];
                if (!file_exists("attachments/" . $folderDate . '/' . $messageId)) {
                    mkdir("attachments/" . $folderDate . '/' . $messageId);
                }    
                $attachmentObj = $service->users_messages_attachments->get($userId, $messageId, $attachmentId);
                $data = $attachmentObj->getData(); //Get data from attachment object
                $remoteFile = decode_content($data);

                $file = __DIR__ . "/attachments/" . $folderDate . '/' . $messageId . '/' . md5(time()) . '_' . $name;
                file_put_contents($file, $remoteFile);



            }
        }

        // Add emails to database
        /*$sqlInsertQuery = "INSERT INTO email_inbox ( message_id, message_date, `from`, from_email, `subject`, email_body )" . 
                            " VALUES ( '" . $messageId . "', '" . date('Y-m-d H:i:s', strtotime($messageDate)) . "', '" . htmlspecialchars($from) . "', '" . $fromAddress . "', '" . htmlspecialchars($subject) . "', '" . base64_encode($html) . "' )";*/
        $sqlInsertQuery = "INSERT INTO email_inbox ( message_id, message_date, `from`, from_email, `subject`, email_body )" . 
                            " VALUES ( '" . $messageId . "', '" . $messageDate . "', '" . htmlspecialchars($from) . "', '" . $fromAddress . "', '" . htmlspecialchars($subject) . "', '" . base64_encode($html) . "' )";

        
        //var_dump($sqlInsertQuery);    
        
        $query = mysqli_query($con, $sqlInsertQuery);
        $msgId = mysqli_insert_id($con);
        $error = mysqli_error($con);
        
        /*if(!empty($error))
        {
            echo $error;
            exit();
        }*/
    
    }
    
    //break;
    
    $saved = true;
}

if(count($messages) == 0)
{
    $saved = true;
}

if($saved)
{
    echo "Success";
}
else
{
    echo $error;
}

?>