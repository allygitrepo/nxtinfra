<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'Admin.php';

class Configrations extends Admin {

	public $company = 'configrations/company';
	public $company_create = 'configrations/company_create';
	public $location = 'configrations/location';
	public $location_create = 'configrations/location';
	public $workflow = 'configrations/workflow';
	public $user = 'configrations/user';

	public function company_manage()
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$type = '4';;
		$data['company'] = $this->MainModel->get_master('master',$type);
		$this->load->view('admin/configrations/company',$data);
	}
	
	
	
	public function ajax_company_list()
	{
		$this->load->model('CompanyModel');
		$list = $this->CompanyModel->get_datatables();
		$w['status'] = 1;
		$data = array();$no=1;
		foreach ($list as $r) {
			$row = array();
			$row[] = $no;
			$row[] = $r->comp_name;
			$row[] = $r->comp_code;
			$row[] = $r->master_name;
			$action='
			<input type="hidden" value="'.$r->comp_name.'" id="state_'.$r->comp_id.'">
			<a data-bs-toggle="modal" onclick="edit_fn('.$r->comp_id.')" href="'.site_url('configrations/edit_company/'.$r->comp_id).'"  class="btn btn-primary px-4 radius-30"><i class="fa fa-edit"></i> Edit</a> <a href="'.site_url('configrations/delete_company/'.$r->comp_id).'" onclick="return confirm("Are you sure ?")" class="btn btn-danger px-4 radius-30"><i class="fa fa-times"></i> Delete</a>';
			$row[] = $action;
			$data[] = $row;
			$no++;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->CompanyModel->count_all(),											                        "recordsFiltered" => $this->CompanyModel->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	
	public function company_add()
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$where['master_type']=4;
		$data['page_name'] ='Add Company';
		$data['button_name'] ='Save';
		$data['vertical'] = $this->MainModel->get_master('master',4);
		$this->load->view('admin/configrations/company_add',$data);
	}
	
	public function edit_company($id)
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$w['comp_id'] = $id;
		$data['vertical'] = $this->MainModel->get_master('master',4);
		$data['row'] = $this->MainModel->get_result('company',$w);
		$data['page_name'] ='Edit Company';
		$data['button_name'] ='Update';
		$this->load->view('admin/configrations/company_add',$data);
	}
	
	
	public function company_create()
	{   
		$data['comp_name'] = $this->input->post('comp_name');
		$data['comp_code'] = $this->input->post('comp_code');
		$data['comp_vertical'] = $this->input->post('comp_vertical');
		$data['budget_control_gst'] = $this->input->post('budget_control_gst');
		$data['comp_addr1'] = $this->input->post('comp_addr1');
		$data['comp_addr2'] = $this->input->post('comp_addr2');
		$data['comp_addr3'] = $this->input->post('comp_addr3');
		$data['comp_mobile'] = $this->input->post('comp_mobile');
		$data['comp_office'] = $this->input->post('comp_office');
		$data['comp_email'] = $this->input->post('comp_email');
		$data['comp_pincode'] = $this->input->post('comp_pincode');
		$data['comp_city'] = $this->input->post('comp_city');
		$data['comp_register_address1'] = $this->input->post('comp_register_address1');
		$data['comp_register_address2'] = $this->input->post('comp_register_address2');
		$data['comp_register_address3'] = $this->input->post('comp_register_address3');
		$data['comp_register_pincode'] = $this->input->post('comp_register_pincode');
		$data['comp_state'] = $this->input->post('comp_state');
		$data['comp_website'] = $this->input->post('comp_website');
		$data['comp_cin_no'] = $this->input->post('comp_cin_no');
		$data['comp_gst_no'] = $this->input->post('comp_gst_no');
		$data['comp_pan_no'] = $this->input->post('comp_pan_no');
		$data['comp_start_date'] = $this->input->post('comp_start_date');
		$data['comp_end_date'] = $this->input->post('comp_end_date');
		$data['general_terms'] = $this->input->post('general_terms');
		$data['header_terms'] = $this->input->post('header_terms');
		$data['comp_slogan'] = $this->input->post('comp_slogan');
$sql="SELECT * FROM `company` WHERE (comp_name='".$data['comp_name']."' || comp_code='".$data['comp_code']."' && status='1')";
		$query = $this->db->query($sql);
		$count= $query->num_rows();
		if ($count>0)
		{
			$this->session->set_flashdata('this_error','Duplicate Company  Name OR Duplicate Company Code Not Allowed');
			return redirect($this->company_create);
		}
		else
		{
		$insert = $this->MainModel->insert_row('company',$data);
		if($insert){
			$file=$_FILES['logo']['name'];
			$ext=@pathinfo($file, PATHINFO_EXTENSION);
			$config = [
				'upload_path' => 'assets/uploads/',
				'allowed_types' => 'jpg|png|jpeg|gif',
				'file_name' =>$data['comp_name'].time().".".$ext,				
				];
			$this->load->library('upload',$config);		
			$this->upload->do_upload('logo');	
			$w['comp_id'] = $insert;
            $p['logo_file_name'] = $config['file_name'];
            $this->MainModel->update_row('company',$w,$p);
		$this->session->set_flashdata('success','Company Created Successfuly');
		return redirect($this->company);
		}
		}
	}


public function company_update()
	{    $w['comp_id'] =$id = $this->input->post('id');
		$data['comp_name'] = $this->input->post('comp_name');
		$data['comp_code'] = $this->input->post('comp_code');
		$data['comp_vertical'] = $this->input->post('comp_vertical');
		$data['budget_control_gst'] = $this->input->post('budget_control_gst');
		$data['comp_addr1'] = $this->input->post('comp_addr1');
		$data['comp_addr2'] = $this->input->post('comp_addr2');
		$data['comp_addr3'] = $this->input->post('comp_addr3');
		$data['comp_mobile'] = $this->input->post('comp_mobile');
		$data['comp_office'] = $this->input->post('comp_office');
		$data['comp_email'] = $this->input->post('comp_email');
		$data['comp_pincode'] = $this->input->post('comp_pincode');
		$data['comp_city'] = $this->input->post('comp_city');
		$data['comp_register_address1'] = $this->input->post('comp_register_address1');
		$data['comp_register_address2'] = $this->input->post('comp_register_address2');
		$data['comp_register_address3'] = $this->input->post('comp_register_address3');
		$data['comp_register_pincode'] = $this->input->post('comp_register_pincode');
		$data['comp_state'] = $this->input->post('comp_state');
		$data['comp_website'] = $this->input->post('comp_website');
		$data['comp_cin_no'] = $this->input->post('comp_cin_no');
		$data['comp_gst_no'] = $this->input->post('comp_gst_no');
		$data['comp_pan_no'] = $this->input->post('comp_pan_no');
		$data['comp_start_date'] = $this->input->post('comp_start_date');
		$data['comp_end_date'] = $this->input->post('comp_end_date');
		$data['general_terms'] = $this->input->post('general_terms');
		$data['header_terms'] = $this->input->post('header_terms');
		$data['comp_slogan'] = $this->input->post('comp_slogan');
 $sql="SELECT * FROM `company` WHERE ((comp_name='".$data['comp_name']."' || comp_code='".$data['comp_code']."') && status='1' && comp_id!='".$id."')";

		$query = $this->db->query($sql);
		$count= $query->num_rows();
		if ($count>0)
		{
			$this->session->set_flashdata('this_error','Duplicate Company  Name OR Duplicate Company Code Not Allowed');
			return redirect('configrations/edit_company/'.$id);
		}
		else
		{
		$insert = $this->MainModel->update_row('company',$w,$data);
		if($insert){
			$file=$_FILES['logo']['name'];
			$ext=@pathinfo($file, PATHINFO_EXTENSION);
			$config = [
				'upload_path' => '/new/assets/uploads/',
				'allowed_types' => 'jpg|png|jpeg|gif',
				'file_name' =>$data['comp_name'].time().".".$ext,				
				];
			$this->load->library('upload',$config);		
			$this->upload->do_upload('logo');	
            $p['logo_file_name'] = $config['file_name'];
           // $this->MainModel->update_row('company',$w,$p);
		$this->session->set_flashdata('success','Company Updated Successfuly');
		return redirect($this->company);
		}
		}
	}
   public function delete_company($id)
		{
			$w['comp_id'] = $id;	
			$data['status']=0;
			$query = $this->MainModel->update_row('company',$w,$data);
		if($query)
			{			
				$this->session->set_flashdata('success','Company Deleted Successfuly');
				return redirect($this->company);
			}
			else
			{
				$this->session->set_flashdata('failure','Error. Try again.');
				return redirect($this->company);
			}		
		}
		
  public function location()
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$w['del']=0;
		$data['location'] = $this->MainModel->get_result('sma_location',$w);
		$this->load->view('admin/configrations/location',$data);
	}
  
  public function location_add()
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$w['status']=1;$w1['1']=1;
		$data['company'] = $this->MainModel->get_result('company',$w);
		$data['state'] = $this->MainModel->get_result('states',$w1);
		$data['page_name'] ='Add Location';
		$data['button_name'] ='Save';
		$this->load->view('admin/configrations/location_add',$data);
	}
	public function location_edit($id)
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$w['status']=1;$w1['1']=1;$w2['id']=$id;
		$data['company'] = $this->MainModel->get_result('company',$w);
		$data['state'] = $this->MainModel->get_result('states',$w1);
		$data['page_name'] ='Edit Location';
		$data['button_name'] ='Update';
	    $data['row'] = $this->MainModel->get_result('sma_location',$w2);
		$this->load->view('admin/configrations/location_add',$data);
	}
	
	public function location_create()
	{   
		$data['loc_name'] = $this->input->post('loc_name');
		$data['loc_comp_id'] = $this->input->post('loc_comp_id');
		$data['loc_contact_person'] = $this->input->post('loc_contact_person');
		$data['loc_email'] = $this->input->post('loc_email');
		$data['loc_contact_person_mobile'] = $this->input->post('loc_contact_person_mobile');
		$data['loc_addr1'] = $this->input->post('loc_addr1');
		$data['loc_state'] = $this->input->post('loc_state');
		$data['loc_contact_person_mobile'] = $this->input->post('loc_mobile');
		$data['loc_gst_no'] = $this->input->post('loc_gst_no');
		$data['loc_pan_no'] = $this->input->post('loc_pan_no');
		$data['status'] = $this->input->post('edstatus');
 $sql="SELECT * FROM `sma_location` WHERE (loc_name='".$data['loc_name']."' && loc_comp_id='".$data['loc_comp_id']."' && del!='1')";

		$query = $this->db->query($sql);
		$count= $query->num_rows();
		if ($count>0)
		{
			$this->session->set_flashdata('this_error','Duplicate Location Name Not Allowed');
			return redirect($this->location_create);
		}
		else
		{
		$insert = $this->MainModel->insert_row('sma_location',$data);
		if($insert){
			
			$this->session->set_flashdata('success','Location Created Successfuly');
		return redirect($this->location);
			}
	  }
	}
	public function location_update()
	{   
		$data['loc_name'] = $this->input->post('loc_name');
		$data['loc_comp_id'] = $this->input->post('loc_comp_id');
		$data['loc_contact_person'] = $this->input->post('loc_contact_person');
		$data['loc_email'] = $this->input->post('loc_email');
		$data['loc_contact_person_mobile'] = $this->input->post('loc_contact_person_mobile');
		$data['loc_addr1'] = $this->input->post('loc_addr1');
		$data['loc_state'] = $this->input->post('loc_state');
		$w['id'] =$id= $this->input->post('id');
		$data['loc_contact_person_mobile'] = $this->input->post('loc_mobile');
		$data['loc_gst_no'] = $this->input->post('loc_gst_no');
		$data['loc_pan_no'] = $this->input->post('loc_pan_no');
		$data['status'] = $this->input->post('edstatus');
 $sql="SELECT * FROM `sma_location` WHERE (loc_name='".$data['loc_name']."' && loc_comp_id='".$data['loc_comp_id']."' && del!='1'  && id!='".$id."')";

		$query = $this->db->query($sql);
		$count= $query->num_rows();
		if ($count>0)
		{
			$this->session->set_flashdata('this_error','Duplicate Location Name Not Allowed');
			return redirect($this->location_create);
		}
		else
		{
		$insert = $this->MainModel->update_row('sma_location',$w,$data);
		if($insert){
			
			$this->session->set_flashdata('success','Location Updated Successfuly');
		return redirect($this->location);
			}
	  }
	}
	 public function delete_location($id)
		{
			$w['id'] = $id;	
			$data['del']=1;
			$query = $this->MainModel->update_row('sma_location',$w,$data);
		if($query)
			{			
				$this->session->set_flashdata('success','Location Deleted Successfuly');
				return redirect($this->location);
			}
			else
			{
				$this->session->set_flashdata('failure','Error. Try again.');
				return redirect($this->location);
			}		
		}
  public function workflow()
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$w['del']=0;
		$data['location'] = $this->MainModel->get_result('sma_location',$w);
		$this->load->view('admin/configrations/workflow',$data);
	}
  
  public function workflow_add()
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$w['status']=1;$w1['1']=1;
		$data['vertical'] = $this->MainModel->get_master('master',4);
		$data['role'] = $this->MainModel->get_master('master',6);
		$data['page_name'] ='Add Workflow';
		$data['button_name'] ='Save';
		$this->load->view('admin/configrations/workflow_add',$data);
	}
	public function workflow_edit($id)
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$w1['1']=1;
		$data['vertical'] = $this->MainModel->get_master('master',4);
		$data['role'] = $this->MainModel->get_master('master',6);
		$data['page_name'] ='Edit Workflow';
		$data['button_name'] ='Update';
		$w['id']=$id;
		$data['row']=$this->MainModel->get_result('sma_workflow',$w);
		$this->load->view('admin/configrations/workflow_add',$data);
	}
	public function ajax_workflow_list()
	{
		$this->load->model('WorkflowModel');
		$list = $this->WorkflowModel->get_datatables();
		$w['status'] = 1;
		$data = array();$no=1;
		foreach ($list as $r) {
			$row = array();
			$row[] = $no;
			$row[] = GetForeignKey('master','id',$r->vertical_type,'master_name');
			$row[] = $r->doc_type;
			$row[] = $r->trans_type;
			$row[] = $r->from_value;
			$row[] = $r->to_value;
			$row[] = GetForeignKey('master','id',$r->approval_role_1,'master_name');
			$row[] = GetForeignKey('master','id',$r->approval_role_2,'master_name');
			$row[] = GetForeignKey('master','id',$r->approval_role_3,'master_name');
			$action='
			
			<a data-bs-toggle="modal" onclick="edit_fn('.$r->id.')" href="'.site_url('configrations/workflow/edit/'.$r->id).'"  class="btn btn-primary px-4 radius-30"><i class="fa fa-edit"></i> Edit</a> <a href="'.site_url('configrations/delete_workflow/'.$r->id).'" onclick="return confirm("Are you sure ?")" class="btn btn-danger px-4 radius-30"><i class="fa fa-times"></i> Delete</a>';
			$row[] = $action;
			$data[] = $row;
			$no++;
		}
		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->WorkflowModel->count_all(),											                        "recordsFiltered" => $this->WorkflowModel->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	public function workflow_create()
	{   
		$data['vertical_type'] = $this->input->post('vertical_type');
		$data['trans_type'] = $this->input->post('trans_type');
		$data['doc_type'] = $this->input->post('doc_type');
		$data['prefix'] = $this->input->post('prefix');
		$data['suffix'] = $this->input->post('suffix');
		$data['from_value'] = $this->input->post('from_value');
		$data['to_value'] = $this->input->post('to_value');
		$data['approval_role_1'] = $this->input->post('approval_role_1');
		$data['approval_role_2'] = $this->input->post('approval_role_2');
		$data['approval_role_3'] = $this->input->post('approval_role_3');
		$insert = $this->MainModel->insert_row('sma_workflow',$data);
		if($insert){
			$this->session->set_flashdata('success','Workflow Created Successfuly');
		   return redirect($this->workflow);
		}
	}
	public function workflow_update()
	{   
		$data['vertical_type'] = $this->input->post('vertical_type');
		$data['trans_type'] = $this->input->post('trans_type');
		$data['doc_type'] = $this->input->post('doc_type');
		$data['prefix'] = $this->input->post('prefix');
		$data['suffix'] = $this->input->post('suffix');
		$data['from_value'] = $this->input->post('from_value');
		$data['to_value'] = $this->input->post('to_value');
		$data['approval_role_1'] = $this->input->post('approval_role_1');
		$data['approval_role_2'] = $this->input->post('approval_role_2');
		$data['approval_role_3'] = $this->input->post('approval_role_3');
		$w['id']= $this->input->post('id');;
		$insert = $this->MainModel->update_row('sma_workflow',$w,$data);
		if($insert){
			$this->session->set_flashdata('success','Workflow Created Successfuly');
		   return redirect($this->workflow);
		}
	}
	public function delete_workflow($id)
		{
			$w['id'] = $id;	
			$data['del']=1;
			$query = $this->MainModel->update_row('sma_workflow',$w,$data);
		if($query)
			{			
				$this->session->set_flashdata('success','Workflow Deleted Successfuly');
				return redirect($this->workflow);
			}
			else
			{
				$this->session->set_flashdata('failure','Error. Try again.');
				return redirect($this->workflow);
			}		
		}
		
  public function user()
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$w['del']=0;
		$this->load->view('admin/configrations/user',$data);
	}	
  public function access()
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$data['role'] = $this->MainModel->get_master('master',6);
		$w['del']=0;
		$this->load->view('admin/configrations/access',$data);
	}	
	public function access_edit($id)
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$data['role'] = $this->MainModel->get_master('master',6);
		$w['del']=0;
		$this->load->view('admin/configrations/access_edit',$data);
	}	
  public function user_add()
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$w['status']=1;$w1['1']=1;
		$data['vertical'] = $this->MainModel->get_master('master',4);
		$data['role'] = $this->MainModel->get_master('master',6);
		$data['page_name'] ='Add Workflow';
		$data['button_name'] ='Save';
		$this->load->view('admin/configrations/workflow_add',$data);
	}
	public function user_edit($id)
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$w1['1']=1;
		$data['vertical'] = $this->MainModel->get_master('master',4);
		$data['role'] = $this->MainModel->get_master('master',6);
		$data['page_name'] ='Edit Workflow';
		$data['button_name'] ='Update';
		$w['id']=$id;
		$data['row']=$this->MainModel->get_result('sma_workflow',$w);
		$this->load->view('admin/configrations/workflow_add',$data);
	}
	public function ajax_user_list()
	{
		$this->load->model('UserModel');
		$list = $this->UserModel->get_datatables();
		$w['status'] = 1;
		$data = array();$no=1;
		foreach ($list as $r) {
			$row = array();
			$row[] = $no;
			$row[] = $r->username;
			$row[] = $r->userid;
			$row[] = $r->email;
			$row[] = $r->mobile_no;
			$row[] = $r->role;
			if( $r->active==1){$s='Active';}else{$s='Inactive';}
			$row[] = $s;
			$action='
			<a data-bs-toggle="modal" onclick="edit_fn('.$r->id.')" href="'.site_url('configrations/user/edit/'.$r->id).'"  class="btn btn-primary px-4 radius-30"><i class="fa fa-edit"></i> Edit</a> <a href="'.site_url('configrations/delete_user/'.$r->id).'" onclick="return confirm("Are you sure ?")" class="btn btn-danger px-4 radius-30"><i class="fa fa-times"></i> Delete</a>';
			$row[] = $action;
			$data[] = $row;
			$no++;
		}
		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->UserModel->count_all(),											                        "recordsFiltered" => $this->UserModel->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	
	
		 
}