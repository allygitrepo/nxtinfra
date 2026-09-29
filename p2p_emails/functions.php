<?php

function listMessages($service, $userId, $optArr = []) 
{
    $pageToken = NULL;
    $messages = array();
    do 
    {
        try 
        {
            if ($pageToken) 
            {
                $optArr['pageToken'] = $pageToken;
            }
            $messagesResponse = $service->users_messages->listUsersMessages($userId, $optArr);
            if ($messagesResponse->getMessages()) 
            {
                $messages = array_merge($messages, $messagesResponse->getMessages());
                $pageToken = $messagesResponse->getNextPageToken();
            }
        } 
        catch (Exception $e) 
        {
            print 'An error occurred: ' . $e->getMessage();
        }
    } while ($pageToken);
    return $messages;
}

function getHeaderArr($dataArr) 
{
    $outArr = [];
    foreach ($dataArr as $key => $val) 
    {
        $outArr[$val->name] = $val->value;
    }
    return $outArr;
}

function base64url_decode($data) 
{
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}

function getMessage($service, $userId, $messageId) 
{
    try 
    {
        $message = $service->users_messages->get($userId, $messageId);
        #print 'Message with ID: ' . $message->getId() . ' retrieved.' . "\n";
        return $message;
    } 
    catch (Exception $e) 
    {
        print 'An error occurred: ' . $e->getMessage();
    }
}

function parse_email($email) 
{
    $result = array();
    for($i = 0; $i < count($email); $i++) 
    {
        $part = $email[$i];
        $mime = $part->mimeType;
        $name = $part->filename;
        if(strlen($name) > 0) 
        {
            $file = array();
            $file["type"] = $mime;
            $file["name"] = $name;
            $file["body"] = $part->body->attachmentId;
            array_push($result, $file);
        }
        else if($mime === "text/plain") 
        {
            $file = array();
            $file["type"] = "text";
            $file["name"] = "email_body_plain.html";
            $file["body"] = decode_content($part->body->data);
            array_push($result, $file);
        }
        else if($mime === "text/html") 
        {
            $file = array();
            $file["type"] = "html";
            $file["name"] = "email_body_html.html";
            $file["body"] = decode_content($part->body->data);
            array_push($result, $file);
        }
        else if(substr($mime, 0, 9) === "multipart") 
        {
            foreach(parse_email($part->parts) as $file) 
            {
                array_push($result, $file);
            }
        }
    }
    return $result;
}

function decode_content($content) 
{
    return base64_decode(str_replace("-", "+", str_replace("_", "/", $content)));
}



?>