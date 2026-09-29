<?php
class ApiModel extends CI_Model {

	public function CheckEmailExist($email)
    {
        return $this->db->where('email',$email)->get('users')->row_array() ? true : false;             
    }

    public function InsertRecord($table,$data)
    {
    	$this->db->insert($table,$data);
    	return $this->db->insert_id();
    }

    public function GetRecord($table,$data)
    {
    	return $this->db->where($data)->get($table)->row_array(); 	
    }

    public function GetResult($table,$data)
    {
    	return $this->db->where($data)->get($table)->result_array(); 
    }

    public function LoadChat($from_id,$to_id)
    {
    return $this->db->query("SELECT chat_id,from_id,to_id,message,created_at FROM `conversations` where from_id = '$from_id' AND to_id = '$to_id' UNION ALL SELECT chat_id,from_id,to_id,message,created_at FROM `conversations` where from_id = '$to_id' AND to_id = '$from_id' order by chat_id asc")->result_array();
    }

    public function ChatList($user_id)
    {
    return $this->db->query("SELECT distinct(to_id) FROM `conversations` where from_id = '$user_id' order by created_at desc")->result_array();
    }

    public function UpdateRow($table,$where,$update)
    {
        $this->db->where($where)->update($table,$update);
        return true;
    }

    



//END OF CLASS
}