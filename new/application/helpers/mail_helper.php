<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

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
            $CI->email->from($sender,'Milner & ORR');
            $CI->email->to($receiver);
            $CI->email->reply_to($sender,'Milner & ORR');
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
}

?>