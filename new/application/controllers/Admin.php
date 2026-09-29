<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('admin_id'))
		return redirect('/');		
	}



	public function dashboard()
	{	$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$var = $this->session->userdata;
		$current_admin=$var['admin_id'];
		$where['assigned_user_id'] = $current_admin;
		$w['assigned_user_id'] = $current_admin;
		$data['page_name'] = 'Dashboard';		
		$this->load->view('admin/dashboard',$data);
	}	

	public function underconstruction()
	{	$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$data['page_name'] = 'Dashboard';		
		$this->load->view('admin/underconstruction',$data);
	}
	
	public function test()
	{
		$data['page_name'] = 'Dashboard';		
		$this->load->view('admin/user/add_user',$data);
	}	

	public function profile()
	{	
		$data['page_name'] = 'Profile';
		$where['id'] = $this->session->userdata('admin_id');
		$data['admin'] = $this->MainModel->get_row('admin',$where);				
        $this->load->view('admin/profile',$data);
	}

	
	public function settings()
	{
		$data['page_name'] = 'Account Settings';		
		$this->load->view('admin/settings',$data);

	}

	public function change_password()
	{

		$this->form_validation->set_rules('current_password', 'Current Password', 'required|min_length[5]');

		$this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[5]');

		$this->form_validation->set_rules('retype_new_password', 'Confirm Password', 'required|min_length[5]|matches[new_password]');

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

		

		if ($this->form_validation->run() == FALSE)

                {

                    $this->settings();

                }

                else

                {

					$where['password'] = md5($this->input->post('current_password'));

					$where['username'] = 'admin';

					$data['password'] = md5($this->input->post('retype_new_password'));

					

					if($this->MainModel->get_row('admin',$where))

					{

						$this->MainModel->update_row('admin',$where,$data);

						$this->session->set_flashdata('success','Password Changed Successfuly');

						return redirect('admin/settings');

					}

					else

					{

						$this->session->set_flashdata('failure','Invalid Attempt Try Again');

						return redirect('admin/settings');

					}

                }

	}

	

	public function upload_picture()
	{		

		if(getimagesize($_FILES['userfile']['tmp_name']))
		{

			$where['username'] = 'admin';

			$data['logo'] = base64_encode(file_get_contents($_FILES['userfile']['tmp_name']));

			if($this->MainModel->update_row('admin',$where,$data))
			{		

				$this->session->set_flashdata('upload_success','Picture Updated Successfuly');

				return redirect('admin/profile');

			}

		}
		else
		{

			$this->session->set_flashdata('upload_failure','Invalid image format');
			return redirect('admin/profile');

		}                		

	}

	

	public function update_profile()
	{		

		$this->form_validation->set_rules('name', 'Name', 'required');

		$this->form_validation->set_rules('phone', 'Phone', 'required');

		$this->form_validation->set_rules('email', 'Email', 'required');

		$this->form_validation->set_rules('business', 'Business', 'required');

		$this->form_validation->set_rules('copyright_text', 'Copyright Text', 'required');

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

		

		if ($this->form_validation->run() == FALSE)
        {

            $this->profile();

        }
        else
        {

			$data['name'] = $this->input->post('name');

			$data['phone'] = $this->input->post('phone');

			$data['email'] = $this->input->post('email');

			$data['business'] = $this->input->post('business');

			$data['copyright_text'] = $this->input->post('copyright_text');

			$where['username'] = 'admin';

			if($this->MainModel->update_row('admin',$where,$data))
			{

				$this->session->set_flashdata('success','Profile Updated Successfuly');

				return redirect('admin/profile');

			}
			else
			{

				$this->session->set_flashdata('failure','Invalid Attempt Try Again');

				return redirect('admin/profile');

			}

        }				

	}





	public function api_keys()

	{

		$data['page_name'] = 'Api Keys settings';	

		$where['id'] = 1;

		$data['api'] = $this->MainModel->get_row('api_keys',$where);	

		$this->load->view('admin/api_keys',$data);

	}



	public function update_api_keys()

	{		

		$this->form_validation->set_rules('google_maps', 'Google Maps', 'required');

		$this->form_validation->set_rules('fcm_customer', 'FCM Customer', 'required');

		$this->form_validation->set_rules('fcm_artist', 'FCM Artist', 'required');



		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

		

		if ($this->form_validation->run() == FALSE)

                {

                        $this->api_keys();

                }

                else

                {

					$data['google_maps'] = $this->input->post('google_maps');

					$data['fcm_customer'] = $this->input->post('fcm_customer');

					$data['fcm_artist'] = $this->input->post('fcm_artist'); 

					$where['id'] = 1;

					if($this->MainModel->update_row('api_keys',$where,$data))

					{

						$this->session->set_flashdata('success','Settings Updated Successfuly');

						return redirect('admin/api-keys');

					}

					else

					{

						$this->session->set_flashdata('failure','Invalid Attempt Try Again');

						return redirect('admin/api-keys');

					}

                }				

	}

	

	

	

	

}