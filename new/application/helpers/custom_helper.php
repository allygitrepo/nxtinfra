<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');
if (! function_exists('GetForeignKey')) {
    function GetForeignKey($table,$where_col,$where_id,$fetch_col)
    {     // get main CodeIgniter object
        $ci = get_instance();    
        // Write your logic as per requirement
       $query = $ci->db->select($fetch_col)->where($where_col,$where_id)->get($table);
       $result = $query->row_array();
	   if(empty($result)){return '';}else{
       return $result[$fetch_col];}
    }
} 
if (! function_exists('GetTitle')) {
    function GetTitle($table,$id,$title)
    {
        // get main CodeIgniter object
        $ci = get_instance();       
        // Write your logic as per requirement
       $query = $ci->db->select($title)->where('id',$id)->get($table);
       $result = $query->row_array();
       return $result[$title];
    }
}
if (! function_exists('DF')) {
    function DF($df)
    {	$timestamp = strtotime($df);
		$new_date = date("d/m/Y", $timestamp);
		echo $new_date; 
    }
}

if (! function_exists('GetCounter')) {
    function GetCounter($table,$where)
    {
      $ci = get_instance();
      $query = $ci->db->where($where,$where)->get($table);
      $result = $query->num_rows();
      return $result;
    }
}

if (! function_exists('GetCategories')) {
    function GetCategories($parent)
    {
      $ci = get_instance();
      $query = $ci->db->query("select *  from category where parent = '$parent'");
      $result = $query->result_array();
      return $result;
    }
}

if (! function_exists('SendNotification')) {
  function  SendNotification($gcm_token,$title,$body,$from_id)
  {
  define('API_ACCESS_KEY','AIzaSyD7Ndnp2Imy17l2uItjR8rqbFRsO2aXcJI'); 

    $registrationIDs = array();

    $fcmMsg = array('body' => $body,'title' => $title,'sound' => "default",'color' => "#203E78");

    $dataArr = array('body' => $body,'title' => $title,'sound' => "default",'color' => "#203E78", 'from_id'=>$from_id, 'type'=>'chat');

    $fcmFields = array('to' => $gcm_token,'priority' => 'high','notification' => $fcmMsg, 'data' => $dataArr);

    $headers = array('Authorization: key=' . API_ACCESS_KEY,'Content-Type: application/json');
 
    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt( $ch,CURLOPT_POST, true);
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers);
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode($fcmFields));
    $result = curl_exec($ch);
    curl_close($ch);
  }
} 

if (!function_exists('send_mail'))
{
  function send_mail($sender,$receiver,$message,$subject)
  { 
      
      $CI = get_instance();
      $CI->load->library('email');
            $config['protocol'] = 'sendmail';
            $config['mailpath'] = '/usr/sbin/sendmail';
            $config['charset'] = 'iso-8859-1';
            $config['mailtype'] = 'html';
            $config['newline'] = "\r\n";
            $config['wordwrap'] = TRUE;

            $CI->email->initialize($config);
            $CI->email->clear();
            $CI->email->from($sender,'YeboAlpha');
            $CI->email->to($receiver);
            $CI->email->reply_to($sender,'YeboAlpha');
            $CI->email->subject($subject);
            $CI->email->message($message);
        
        if($CI->email->send())
            {
                return true;
            }
            else
            {
                return false;
            }
  }
} function Getcity($id)
    {/*
       $ci = get_instance();       
       $query = $ci->db->select($title)->where('id',$id)->get($table);
       $result = $query->row_array();*/
       return 'test' ;//$result[$title];
    }?>