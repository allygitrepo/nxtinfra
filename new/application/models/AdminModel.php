<?php

class AdminModel extends CI_Model {
	
	public function getCurrentTimestamp()
	{
		return date('Y-m-d H:i:s');
	}
	
	public function authentication($username,$password)
	{
		$current_timestamp = $this->getCurrentTimestamp();
		$query = $this->db->where(['username like binary'=>$username,'password like binary'=>$password])->get('admin');
		if($query->num_rows())
		{
			$this->db->where('username','admin')->update('admin',['last_login'=>$current_timestamp]);
			return $query;
		}
		else
		{
			return false;
		}	
	}
	public function getAdminData()
	{
		$query = $this->db->where(['username'=>'admin'])->get('admin');
		$row = $query->row();
		return $row;
	}
	public function change_password($current_password,$new_password,$retype_new_password)
	{
		$current_timestamp = $this->getCurrentTimestamp();
		$fetchAdminPassword = $this->getAdminData();
		
		if($fetchAdminPassword->password==md5($current_password) && $new_password == $retype_new_password)
		{		
	
		$data=array('password'=>md5($retype_new_password),'modified'=>$current_timestamp);
		$this->db->where('username','admin');
		$this->db->update('admin',$data);		
		return true;
		}
		else
		{
		return false;
		}	
	}
	public function update_profile($name,$phone,$email,$business,$copyright_text)
	{
		$current_timestamp = $this->getCurrentTimestamp();
			
		$data=array('name'=>$name,'phone'=>$phone,'email'=>$email,'business'=>$business,'copyright_text'=>$copyright_text,'modified'=>$current_timestamp);
		$query = $this->db->where('username','admin');
		$this->db->update('admin',$data);
		if($query)
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