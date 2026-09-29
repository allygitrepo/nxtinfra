<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class LoginController extends MY_Controller {


	public function index()
	{
		if($this->session->userdata('admin_id'))
			return redirect('dashboard');	
		$where['username'] = 'admin';
		$data['admin'] = $this->MainModel->get_row('admin',$where);	
		$this->load->view('Login',$data);
	}	

	public function Authentication()
	{
		$this->form_validation->set_rules('username', 'Username', 'required|min_length[5]');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_error_delimiters('<div class="alert alert-warning">','</div>');	

		if($this->form_validation->run()) 
		{ 
			$username = $this->input->post('username');
			$password = md5($this->input->post('password'));			
			$results = $this->AdminModel->authentication($username,$password);
			if($results)
			{
				$rowcount = $results->num_rows();
			}
			else
				$rowcount = 0;
			//$rowcount = isset($rowcount) ? $results->num_rows() : 0;			
			if($rowcount) 
			{
				$admin_id = $results->row()->id;
				$defaultbranchid = $results->row()->defaultbranchid;
				$this->session->set_userdata('admin_id',$admin_id);
				$this->session->set_userdata('username',$username);
				$this->session->set_userdata('defbranchid',$defaultbranchid);	
				$branchname = GetForeignKey('branch','id',$defaultbranchid,'branch_name');	
				$this->session->set_userdata('defbranchname',$branchname);		
				$role = GetForeignKey('admin','id',$admin_id,'role');
				$per = GetForeignKey('role','id',$role,'permission');
				$this->session->set_userdata('permission',$per);
				return redirect('dashboard');
			}
			else 
			{
				$this->session->set_flashdata('login_failed','Invalid Username/Password.');
				return redirect('Login');

			}
		} 
		else 
		{
			$this->load->view('Login');
		}

	}

	public function logout()
	{
		$this->session->unset_userdata('admin_id');
		return redirect('Login');
	}

}