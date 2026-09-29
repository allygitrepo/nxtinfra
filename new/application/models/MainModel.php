<?php

class MainModel extends CI_Model {

	public function insert_row($table,$data)
	{
		$this->db->insert($table,$data);
		return $this->db->insert_id();
	}

	public function get_row($table,$where)
	{ 
		return $this->db->where($where)->get($table)->row_array();
	}

	public function get_result($table,$where)
	{
		// print_r($where);exit;
		return $this->db->where($where)->get($table)->result_array();
	}
	
	public function get_master($table,$type)
	{
		// print_r($where);exit;
		$array = array('master_type' => $type, 'status' => 1);
		return $this->db->where($array)->get($table)->result_array();
	}
	
	
	
		
	public function get_page_result($limit, $start,$table,$where) 
	{
        $this->db->limit($limit, $start);
		$this->db->where($where);
        $query = $this->db->get($table);
        return $query->result_array();
    }
	
	public function get_result_orderby($table,$where)
	{
		return $this->db->where($where)->order_by('id','ASC')->get($table)->result_array();
	}
	
	public function get_result_orderby_menu($table,$where)
	{
		return $this->db->where($where)->order_by('order_no','ASC')->get($table)->result_array();
	}

	public function update_row($table,$where,$data)
	{
		if($this->db->where($where)->update($table,$data))
		{
			return true;
		} 
		return false;
	}

	public function delete_row($table,$where)
	{
		if($this->db->where($where)->delete($table))
		{
			return true;
		} 
		return false;
	}	

	function social_login($profile_id,$full_name,$profile_picture,$provider)
	{
	    $res = $this->db->where('email',$profile_id)->get('users')->row_array();
	    if($res)
	    {
	        return $res['id'];
	    }
	    else
	    {			
	        $this->db->insert('users',['email'=>$profile_id,'full_name'=>$full_name,'profile_picture'=>$profile_picture,'signup_type'=>$provider,'password'=>sha1($profile_id),'verification_status'=>1]);
	        return $this->db->insert_id();
	    }
	}

	public function custom_query($query)
	{
		return $this->db->query($query)->result_array();
	}

//END OF MODEL

		}