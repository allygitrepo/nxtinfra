<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'Admin.php';

class Master extends Admin {

	public $manage_master = 'master/manage_master';
	public $states = 'master/states';
	public $city = 'master/city';
	public $products = 'master/products';
	public $account = 'master/accounts';
	public $gst = 'master/gst';
	public $cost_add = 'master/cost_add';
	public $cost = 'master/cost';
	public $supplier = 'master/supplier';

	
	
	public function manage_master()
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$where['status'] = 1;
		$w['1'] = 1;
		$data['master_type'] = $this->MainModel->get_result('master_type',$w);
		$data['master'] = $this->MainModel->get_result('master',$where);
		$this->load->view('admin/master/manage_master',$data);
	}
	
	public function ajax_master_list()
	{
		$this->load->model('SettingMasterModel');
		$list = $this->SettingMasterModel->get_datatables();
		$w['status'] = 1;/*List of staff*/
		$data = array();$no=1;
		foreach ($list as $r) {
			$row = array();
			$row[] = $no;
			$row[] = $r->master_name;
			$row[] = $r->master_type_name;
			$action='
			<input type="hidden" value="'.$r->master_name.'" id="master_name_'.$r->mid.'">
			<input type="hidden" value="'.$r->master_type.'" id="master_type_'.$r->mid.'">
			<a data-bs-toggle="modal" onclick="edit_fn('.$r->mid.')" data-bs-target="#editmodel" class="btn btn-primary px-4 radius-30"><i class="fa fa-edit"></i> Edit</a> <a href="'.site_url('master/delete_master/'.$r->mid).'" onclick="return confirm("Are you sure ?")" class="btn btn-danger px-4 radius-30"><i class="fa fa-times"></i> Delete</a>';
			$row[] = $action;
			$data[] = $row;
			$no++;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->SettingMasterModel->count_all(),											                        "recordsFiltered" => $this->SettingMasterModel->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	
	public function create_master()
	{   
		$data['master_type'] = $this->input->post('master_type');
		$data['master_name'] = $this->input->post('master_name');
		$sql="SELECT * FROM `master` WHERE (master_name='".$data['master_name']."' && master_type='".$data['master_type']."' && status='1')";
		$query = $this->db->query($sql);
		$count= $query->num_rows();
		
		if ($count>0)
		{
			return redirect($this->manage_master);
		}
		else
		{
		$insert = $this->MainModel->insert_row('master',$data);
		if($insert){
		$this->session->set_flashdata('success','Master Created Successfuly');
		return redirect($this->manage_master);
		}
		}
	}

   public function delete_master($id)
		{
			$w['id'] = $id;	
			$data['status']=0;
			$query = $this->MainModel->update_row('master',$w,$data);
		if($query)
			{			
				$this->session->set_flashdata('success','Master Deleted Successfuly');
				return redirect($this->manage_master);
			}
			else
			{
				$this->session->set_flashdata('failure','Error. Try again.');
				return redirect($this->manage_master);
			}		
		}
		
  public function ajax_update_master()
	{   
		$w['id'] = $this->input->post('edid_id');
		$data['master_name'] = $this->input->post('edit_name');
		$data['master_type'] = $this->input->post('mt');
		$sql="SELECT * FROM `master` WHERE (master_name='".$data['master_name']."' && master_type='".$data['master_type']."')";
		$query = $this->db->query($sql);
		$count= $query->num_rows();
		if ($count>0)
		{
			echo 0;
		}
		else
		{	$query = $this->MainModel->update_row('master',$w,$data);
		     echo 1;
		}
	}
	
	public function ajax_save_master()
	{   
	    $data['master_name'] = $this->input->post('name');
		$data['master_type'] = $this->input->post('master_id');
		$sql="SELECT * FROM `master` WHERE (master_name='".$data['master_name']."' && master_type='".$data['master_type']."')";
		$query = $this->db->query($sql);
		$count= $query->num_rows();
		if ($count>0)
		{
			echo 0;
		}
		else
		{	$insert = $this->MainModel->insert_row('master',$data);
		     echo $insert;
		}
	}
	
	public function states()
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$where['status'] = 1;
		$this->load->view('admin/master/states',$data);
	}
	public function ajax_states_list()
	{
		$this->load->model('StatesModel');
		$list = $this->StatesModel->get_datatables();
		$w['status'] = 1;/*List of staff*/
		$data = array();$no=1;
		foreach ($list as $r) {
			$row = array();
			$row[] = $no;
			$row[] = $r->state_name;
			$action='
			<input type="hidden" value="'.$r->state_name.'" id="state_'.$r->id.'">
			<a data-bs-toggle="modal" onclick="edit_fn('.$r->id.')" data-bs-target="#editmodel" class="btn btn-primary px-4 radius-30"><i class="fa fa-edit"></i> Edit</a> <a href="'.site_url('master/delete_states/'.$r->id).'" onclick="return confirm("Are you sure ?")" class="btn btn-danger px-4 radius-30"><i class="fa fa-times"></i> Delete</a>';
			$row[] = $action;
			$data[] = $row;
			$no++;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->StatesModel->count_all(),											                        "recordsFiltered" => $this->StatesModel->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	public function create_state()
	{   
		$data['state_name'] = $this->input->post('state_name');
		$sql="SELECT * FROM `states` WHERE (state_name='".$data['state_name']."')";
		$query = $this->db->query($sql);
		$count= $query->num_rows();
		if ($count>0)
		{
			$this->session->set_flashdata('this_error','Duplicate State Not Allowed');
			return redirect($this->states);
		}
		else
		{
		$insert = $this->MainModel->insert_row('states',$data);
		if($insert){
		$this->session->set_flashdata('success','State Created Successfuly');
		return redirect($this->states);
		}
		}
	}
	
	public function delete_states($id)
		{
			$where['id'] = $id;	
			$query = $this->MainModel->delete_row('states',$where);
		if($query)
			{			
				$this->session->set_flashdata('success','States Deleted Successfuly');
				return redirect($this->states);
			}
			else
			{
				$this->session->set_flashdata('failure','Error. Try again.');
				return redirect($this->states);
			}		
		}
	
	public function ajax_update_state()
	{   
		$w['id'] = $this->input->post('edid_id');
		$data['state_name'] = $this->input->post('state_name');
		$sql="SELECT * FROM `states` WHERE (state_name='".$data['state_name']."')";
		$query = $this->db->query($sql);
		$count= $query->num_rows();
		if ($count>0)
		{
			echo 0;
		}
		else
		{	$query = $this->MainModel->update_row('states',$w,$data);
		     echo 1;
		}
	}
	
	public function city()
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$where['1'] = 1;
		$data['state'] = $this->MainModel->get_result('states',$where);
		$this->load->view('admin/master/city',$data);
	}
	
	public function ajax_city_list()
	{
		$this->load->model('CityModel');
		$list = $this->CityModel->get_datatables();
		$w['status'] = 1;/*List of staff*/
		$data = array();$no=1;
		foreach ($list as $r) {
			$row = array();
			$row[] = $no;
			$row[] = $r->city_name;
			$row[] = $r->state_name;
			$action='
			<input type="hidden" value="'.$r->city_name.'" id="city_'.$r->cid.'">
			<input type="hidden" value="'.$r->state_name.'" id="statename_'.$r->cid.'">
			<input type="hidden" value="'.$r->states_id.'" id="states_id_'.$r->cid.'">
			<a data-bs-toggle="modal" onclick="edit_fn('.$r->cid.')" data-bs-target="#editmodel" class="btn btn-primary px-4 radius-30"><i class="fa fa-edit"></i> Edit</a> <a href="'.site_url('master/delete_city/'.$r->cid).'" onclick="return confirm("Are you sure ?")" class="btn btn-danger px-4 radius-30"><i class="fa fa-times"></i> Delete</a>';
			$row[] = $action;
			$data[] = $row;
			$no++;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->CityModel->count_all(),											                        "recordsFiltered" => $this->CityModel->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	public function create_city()
	{   
		$data['states_id'] = $this->input->post('state_id');
		$data['city_name'] = $this->input->post('city_name');
		$sql="SELECT * FROM `cities` WHERE (city_name='".$data['city_name']."' && states_id='".$data['states_id']."')";
		$query = $this->db->query($sql);
		$count= $query->num_rows();
		if ($count>0)
		{
			$this->session->set_flashdata('this_error','Duplicate City Name Not Allowed');
			return redirect($this->city);
		}
		else
		{
		$insert = $this->MainModel->insert_row('cities',$data);
		if($insert){
		$this->session->set_flashdata('success','City Created Successfuly');
		return redirect($this->city);
		}
		}
	}
	public function ajax_update_city()
	{   
		$w['id'] = $this->input->post('edid_id');
		$data['city_name'] = $this->input->post('city_name');
		$data['states_id'] = $this->input->post('state_id');
		$sql="SELECT * FROM `cities` WHERE (city_name='".$data['city_name']."' && states_id='".$data['states_id']."')";
		$query = $this->db->query($sql);
		$count= $query->num_rows();
		if ($count>0)
		{
			echo 0;
		}
		else
		{	$query = $this->MainModel->update_row('cities',$w,$data);
		     echo 1;
		}
	}
	
	public function delete_city($id)
		{
			$where['id'] = $id;	
			$query = $this->MainModel->delete_row('cities',$where);
		if($query)
			{			
				$this->session->set_flashdata('success','City Deleted Successfuly');
				return redirect($this->city);
			}
			else
			{
				$this->session->set_flashdata('failure','Error. Try again.');
				return redirect($this->city);
			}		
		}
		
		
  public function accounts()
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$this->load->view('admin/master/account',$data);
	}		
		
  
	public function ajax_accounts_list()
	{
		$this->load->model('AccountModel');
		$list = $this->AccountModel->get_datatables();
		$w['status'] = 1;/*List of staff*/
		$data = array();$no=1;
		foreach ($list as $r) {
			$row = array();
			$row[] = $no;
			$row[] = $r->account_name;
			if($r->account_type=='A'){$at='Purchase';}
			if($r->account_type=='B'){$at='Bank';}
			if($r->account_type=='D'){$at='Deduction';}
			if($r->account_type=='E'){$at='Expense';}
			$row[] = $at;
			$row[] = $r->master_name;
			$action='<a href="'.site_url('master/accounts/edit/'.$r->aid).'"class="btn btn-primary px-4 radius-30"><i class="fa fa-edit"></i> Edit</a> <a href="'.site_url('master/delete_accounts/'.$r->aid).'" onclick="return confirm("Are you sure ?")" class="btn btn-danger px-4 radius-30"><i class="fa fa-times"></i> Delete</a>';
			$row[] = $action;
			$data[] = $row;
			$no++;
		}
		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->AccountModel->count_all(),											                        "recordsFiltered" => $this->AccountModel->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	
   public function account_add()
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$data['page_name']='Add Account';
		$data['button_name']='save';
		$where['master_type']=4;
		$data['vertical'] = $this->MainModel->get_master('master',4);
		$this->load->view('admin/master/account_add',$data);
	}

	public function account_create()
	{   
		$data['account_name'] = $this->input->post('account_name');
		$data['account_type'] = $this->input->post('account_type');
		$data['vertical_type'] = $this->input->post('vertical_type');
		$data['tds_flag'] = $this->input->post('tds_flag');
		$s=$this->input->post('status');
		if($s==1){$data['status']=1;}else{$data['status']=0;}
		$sql="SELECT * FROM `account_mst` WHERE (account_name='".$data['account_name']."' &&  vertical_type='".$data['vertical_type']."' && del!=1)";
		$query = $this->db->query($sql);
		$count= $query->num_rows();
		if ($count>0)
		{
			$this->session->set_flashdata('this_error','Duplicate Account Name Not Allowed');
			return redirect($this->account);
		}
		else
		{
		$insert = $this->MainModel->insert_row('account_mst',$data);
		if($insert){
		$this->session->set_flashdata('success','Account Created Successfuly');
		return redirect($this->account);
		}
	}
 }
 
 public function account_edit($id)
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$data['page_name']='Edit Account';
		$data['button_name']='Update';
		$where['id']=$id;
		$data['vertical'] = $this->MainModel->get_master('master',4);
		$data['account'] = $this->MainModel->get_result('account_mst',$where);
		$this->load->view('admin/master/account_add',$data);
	}
	
 public function account_update()
	{
		$data['account_name'] = $this->input->post('account_name');
		$data['account_type'] = $this->input->post('account_type');
		$data['vertical_type'] = $this->input->post('vertical_type');
		$data['tds_flag'] = $this->input->post('tds_flag');
		$w['id'] = $this->input->post('id');
		$s=$this->input->post('status');
		if($s==1){$data['status']=1;}else{$data['status']=0;}
		
		 $sql="SELECT * FROM `account_mst` WHERE (account_name='".$data['account_name']."' &&  vertical_type='".$data['vertical_type']."' && del!=1 && id!='".$w['id']."')";
	
		$query = $this->db->query($sql);
		 $count= $query->num_rows();
		if ($count>0)
		{
			$this->session->set_flashdata('this_error','Duplicate Account Name Not Allowed');
			return redirect(site_url('master/accounts/edit/'.$w['id']));
		}
		else
		{
		$insert = $this->MainModel->update_row('account_mst',$w,$data);
		if($insert){
		$this->session->set_flashdata('success','Account Updated Successfuly');
		return redirect($this->account);
		}
	  }
	}
	
	
	public function gst()
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
	    $w['del']=0;
		$data['gst'] = $this->MainModel->get_result('gst_mst',$w);
		$this->load->view('admin/master/gst',$data);
	}
	

	public function ajax_get_gst_account()
	{
		$w['vertical_type'] = $this->input->post('mid');
		$account_mst = $this->MainModel->get_result('account_mst',$w);
		echo '<option value="">Select</option>.';
		foreach($account_mst as $at){
			echo'<option value="'.$at['id'].'" >'.$at['account_name'].'</option>';
		
			}
	}
	public function gst_add()
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$data['page_name']='Add GST';
		$data['button_name']='Save';
		$data['vertical'] = $this->MainModel->get_master('master',4);
		$this->load->view('admin/master/gst_add',$data);
	}
	public function gst_create()
	{
		$data['gst_name'] = $this->input->post('gst_name');
		$data['sgst_account_id'] = $this->input->post('sgst');
		$data['vertical_type'] = $this->input->post('vertical_type');
		$data['cgst_account_id'] = $this->input->post('cgst');
		$data['igst_account_id'] = $this->input->post('igst');
		$data['igst'] = $this->input->post('rate');
		$data['sgst']=$data['cgst']=($data['igst']/2);
		$s=$this->input->post('status');
		if($s==1){$data['status']='Y';}else{$data['status']='N';}
		$insert = $this->MainModel->insert_row('gst_mst',$data);
		if($insert){
		$this->session->set_flashdata('success','GST Created Successfuly');
		return redirect($this->gst);
		}else{
		$this->session->set_flashdata('error','Error');
		return redirect($this->gst);
	   }
	}
	public function gst_update()
	{
		$data['gst_name'] = $this->input->post('gst_name');
		$w['id']= $this->input->post('id');
		$data['sgst_account_id'] = $this->input->post('sgst');
		$data['vertical_type'] = $this->input->post('vertical_type');
		$data['cgst_account_id'] = $this->input->post('cgst');
		$data['igst_account_id'] = $this->input->post('igst');
		$data['igst'] = $this->input->post('rate');
		$data['sgst']=$data['cgst']=($data['igst']/2);
		$s=$this->input->post('status');
		
		if($s==1){$data['status']='Y';}else{$data['status']='N';}
		$insert = $this->MainModel->update_row('gst_mst',$w,$data);
		if($insert){
		$this->session->set_flashdata('success','GST Updated Successfuly');
		return redirect($this->gst);
		}else{
		$this->session->set_flashdata('error','Error');
		return redirect($this->gst);
	   }
	}
	
	
	public function gst_edit($id)
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$data['page_name']='Edit GST';
		$data['button_name']='Update';
		$data['vertical'] = $this->MainModel->get_master('master',4);
		$w['id']=$id;
		$data['row'] = $this->MainModel->get_result('gst_mst',$w);
		$this->load->view('admin/master/gst_add',$data);
	}
	public function gst_delete($id)
		{
			$w['id'] = $id;	
			$data['del']=1;
			$query = $insert = $this->MainModel->update_row('gst_mst',$w,$data);
		if($query)
			{			
				$this->session->set_flashdata('success','GST Deleted Successfuly');
				return redirect($this->gst);
			}
			else
			{
				$this->session->set_flashdata('failure','Error. Try again.');
				return redirect($this->gst);
			}		
		}

	public function cost()
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
	    $w['1']=1;
		$data['1'] = 1;
		$this->load->view('admin/master/cost',$data);
	}
	public function ajax_cost_list()
	{
		$this->load->model('CostModel');
		$list = $this->CostModel->get_datatables();
		$data = array();$no=1;
		foreach ($list as $r) {
			$row = array();
			$row[] = $no;
			if($r->budget_name!=''){$bg=GetForeignKey('master','id',$r->budget_name,'master_name');}else{$bg=$r->budget_name;}
			if($r->project!=''){$cn=GetForeignKey('company','comp_id',$r->project,'comp_name');}else{$cn=$r->project;}
			$row[] = $bg;///sma_budget_name
			$row[] = $cn;//compnay
			$row[] = $r->budget_head;
			$row[] = $r->total_budget;
			$row[] = $r->balance_budget;
			$action='
			<a  href="'.site_url('master/cost-center/edit/'.$r->id).'" class="btn btn-primary px-4 radius-30"><i class="fa fa-edit"></i> Edit</a> <a href="'.site_url('master/delete_cost/'.$r->id).'" onclick="return confirm("Are you sure ?")" class="btn btn-danger px-4 radius-30"><i class="fa fa-times"></i> Delete</a>';
			$row[] = $action;
			$data[] = $row;
			$no++;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->CostModel->count_all(),											                        "recordsFiltered" => $this->CostModel->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	
	
	public function cost_add()
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$data['page_name']='Add Cost Center';
		$data['button_name']='Save';
		$w['1']=1;
		$data['cg'] = $this->MainModel->get_master('master',2);
		$data['company'] = $this->MainModel->get_result('company',$w);
		$this->load->view('admin/master/cost_add',$data);
	}
	
	public function cost_edit($id)
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$data['page_name']='Edit Cost Center';
		$data['button_name']='Update';
		$w['1']=1;
		$w2['id']=$id;
		$data['cg'] = $this->MainModel->get_master('master',2);
		$data['company'] = $this->MainModel->get_result('company',$w);
		$data['row'] = $this->MainModel->get_result('sma_budget',$w2);
		$this->load->view('admin/master/cost_add',$data);
	}
	
	public function cost_create()
	{
		$data['budget_head'] = $this->input->post('budget_head');
		$data['project'] = $this->input->post('project');
		$data['budget_name'] = $this->input->post('budget_name');
		$data['total_budget'] = $this->input->post('total_budget');
		$data['balance_budget'] = $this->input->post('balance_budget');
		$s=$this->input->post('status');
		if($s==1){$data['budget_status']='Y';}else{$data['budget_status']='N';}
		
		 $sql="SELECT * FROM `sma_budget` WHERE (budget_head='".$data['budget_head']."' && project='".$data['project']."' && budget_name='".$data['budget_name']."')";
		$query = $this->db->query($sql);
		$count= $query->num_rows();
		if ($count>0)
		{
			$this->session->set_flashdata('this_error','Duplicate Entry Not Allowed');
			return redirect($this->cost_add);
			
		}
		else{
			$insert = $this->MainModel->insert_row('sma_budget',$data);
			if($insert){
			$this->session->set_flashdata('success','Cost Center Created Successfuly');
			return redirect($this->cost);
			}else{
			$this->session->set_flashdata('error','Error');
			return redirect($this->cost);
		   }
		}
	}
	
	public function cost_update()
	{
		$data['budget_head'] = $this->input->post('budget_head');
		$data['project'] = $this->input->post('project');
		$w['id']=$id = $this->input->post('id');
		$data['budget_name'] = $this->input->post('budget_name');
		$data['total_budget'] = $this->input->post('total_budget');
		$data['balance_budget'] = $this->input->post('balance_budget');
		$s=$this->input->post('status');
		if($s==1){$data['budget_status']='Y';}else{$data['budget_status']='N';}
		
		$sql="SELECT * FROM `sma_budget` WHERE (budget_head='".$data['budget_head']."' && project='".$data['project']."' && budget_name='".$data['budget_name']."' && id!='".$id."')";
		$query = $this->db->query($sql);
		$count= $query->num_rows();
		if ($count>0)
		{
			$this->session->set_flashdata('this_error','Duplicate Entry Not Allowed');
			return redirect('master/cost-center/edit/'.$id);
			
		}
		else{
			$insert = $this->MainModel->update_row('sma_budget',$w,$data);
			if($insert){
			$this->session->set_flashdata('success','Cost Center Updated Successfuly');
			return redirect($this->cost);
			}else{
			$this->session->set_flashdata('error','Error');
			return redirect($this->cost);
		   }
		}
	}
	
	public function delete_cost($id)
		{
			$where['id'] = $id;	
			$data['del'] =1;
			$query = $this->MainModel->update_row('sma_budget',$where,$data);
		if($query)
			{			
				$this->session->set_flashdata('success','States Deleted Successfuly');
				return redirect($this->cost);
			}
			else
			{
				$this->session->set_flashdata('failure','Error. Try again.');
				return redirect($this->cost);
			}		
	}
	
	public function products()
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$this->load->view('admin/master/products',$data);
	}	
		
	public function ajax_products_list()
	{
		$this->load->model('ProductModel');
		$list = $this->ProductModel->get_datatables();
		$w['status'] = 1;/*List of staff*/
		$data = array();$no=1;
		foreach ($list as $r) {
			$row = array();
			$row[] = $no;
			$row[] = $r->name;
			$row[] = GetForeignKey('master','id',$r->vertical_type,'master_name');
			$row[] = GetForeignKey('master','id',$r->product_group,'master_name');
			$row[] = $r->uom;
			$row[] = GetForeignKey('account_mst','id',$r->account_id,'account_name'); 
			
			$action='<a href="'.site_url('master/products/edit/'.$r->id).'"class="btn btn-primary px-4 radius-30"><i class="fa fa-edit"></i> Edit</a> <a href="'.site_url('master/delete_products/'.$r->id).'" onclick="return confirm("Are you sure ?")" class="btn btn-danger px-4 radius-30"><i class="fa fa-times"></i> Delete</a>';
			$row[] = $action;
			$data[] = $row;
			$no++;
		}
		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->ProductModel->count_all(),											                        "recordsFiltered" => $this->ProductModel->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	
	public function product_add()
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$data['page_name']='Add Product';
		$data['button_name']='Save';
		$data['vertical'] = $this->MainModel->get_master('master',4);
		$data['product_group'] = $this->MainModel->get_master('master',1);
		$data['units'] = $this->MainModel->get_master('master',3);
		$w['del']='';
		$data['account_mst'] = $this->MainModel->get_result('account_mst',$w);
		$this->load->view('admin/master/product_add',$data);
	}
	public function get_ac_for_add_product()
	{
		$vt = $this->input->post('vt');
		$w['vertical_type']=$vt;
		$account_mst = $this->MainModel->get_result('account_mst',$w);
		echo '<option value="">Select</option>';
		foreach($account_mst as $acc){
			echo '<option value="'.$acc['id'].'">'.$acc['account_name'].'</option>';
			}
	}
	
	public function get_gst_for_add_product()
	{
		$vt = $this->input->post('vt');
		$w['vertical_type']=$vt;
		$gst = $this->MainModel->get_result('gst_mst ',$w);
		echo '<option value="">Select</option>';
		foreach($gst as $g){
			echo '<option value="'.$g['id'].'">'.$g['gst_name'].'</option>';
			}
	}
	public function product_edit($id)
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$data['page_name']='Edit Product';
		$data['button_name']='Udpate';
		$data['vertical'] = $this->MainModel->get_master('master',4);
		$data['product_group'] = $this->MainModel->get_master('master',1);
		$data['units'] = $this->MainModel->get_master('master',3);
		$w['del']='';$w2['id']=$id;
		$data['account_mst'] = $this->MainModel->get_result('account_mst',$w);
		$data['product'] = $this->MainModel->get_result('sma_product',$w2);
		$this->load->view('admin/master/product_add',$data);
		
	}
	public function product_create()
	{
		$data['name'] = $this->input->post('name');
		$data['vertical_type'] = $this->input->post('vertical_type');
		$data['po_threashold'] = $this->input->post('po_threashold');
		$data['product_group'] = $this->input->post('product_group');
		$data['category'] = $this->input->post('category');
		$data['uom'] = $this->input->post('uom');
		$data['tolerance_level'] = $this->input->post('tolerance_level');
		$data['gst_type'] = $this->input->post('gst_type');
		$data['account_id'] = $this->input->post('account_id');
		$data['hsn_code'] = $this->input->post('hsn_code');
		/*$s=$this->input->post('status');
		if($s==1){$data['budget_status']='Y';}else{$data['budget_status']='N';}*/
		
		$sql="SELECT * FROM `sma_product` WHERE (name='".$data['name']."' && vertical_type='".$data['vertical_type']."' && del!='1')";
		$query = $this->db->query($sql);
		$count= $query->num_rows();
		if ($count>0)
		{
			$this->session->set_flashdata('this_error','Duplicate Entry Not Allowed');
			return redirect($this->product_add);
			
		}
		else{
			$insert = $this->MainModel->insert_row('sma_product',$data);
			if($insert){
			$this->session->set_flashdata('success','Product Created Successfuly');
			return redirect($this->products);
			}else{
			$this->session->set_flashdata('error','Error');
			return redirect($this->products);
		   }
		}
	}
	
	public function product_update()
	{
		$data['name'] = $this->input->post('name');
		$w['id'] = $this->input->post('id');
		$data['vertical_type'] = $this->input->post('vertical_type');
		$data['po_threashold'] = $this->input->post('po_threashold');
		$data['product_group'] = $this->input->post('product_group');
		$data['category'] = $this->input->post('category');
		$data['uom'] = $this->input->post('uom');
		$data['tolerance_level'] = $this->input->post('tolerance_level');
		$data['gst_type'] = $this->input->post('gst_type');
		$data['account_id'] = $this->input->post('account_id');
		$data['hsn_code'] = $this->input->post('hsn_code');
		/*$s=$this->input->post('status');
		if($s==1){$data['budget_status']='Y';}else{$data['budget_status']='N';}*/
		
		$sql="SELECT * FROM `sma_product` WHERE (name='".$data['name']."' && vertical_type='".$data['vertical_type']."' && del!='1' && id!=".$w['id'].")";
		$query = $this->db->query($sql);
		$count= $query->num_rows();
		if ($count>0)
		{
			$this->session->set_flashdata('this_error','Duplicate Entry Not Allowed');
			return redirect('master/products/edit/'.$w['id']);
			
		}
		else{
			$insert = $this->MainModel->update_row('sma_product',$w,$data);
			if($insert){
			$this->session->set_flashdata('success','Product Updated Successfuly');
			return redirect($this->products);
			}else{
			$this->session->set_flashdata('error','Error');
			return redirect($this->products);
		   }
		}
	}
	
	public function supplier()
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$where['1'] = 1;
		$data['service'] = $this->MainModel->get_result('sma_party_mst',$where);
		$where['status'] = 1;
		$this->load->view('admin/master/supplier',$data);
	}
	public function ajax_supplier_list()
	{
		$this->load->model('SupplierModel');
		$list = $this->SupplierModel->get_datatables();
		$w['status'] = 1;/*List of staff*/
		$data = array();$no=1;
		foreach ($list as $r) {
			$row = array();
			$row[] = $no;
			$row[] = $r->party_name;
			$row[] = GetForeignKey('master','id',$r->party_type,'master_name');
			$row[] = GetForeignKey('cities','id',$r->party_city,'city_name');
			$row[] =$r->party_gst_number;
			$row[] =$r->party_pan_number;
			$action='<a href="'.site_url('master/supplier/edit/'.$r->id).'"class="btn btn-primary px-4 radius-30"><i class="fa fa-edit"></i> Edit</a> <a href="'.site_url('master/delete_supplier/'.$r->id).'" onclick="return confirm("Are you sure ?")" class="btn btn-danger px-4 radius-30"><i class="fa fa-times"></i> Delete</a>';
			$row[] = $action;
			$data[] = $row;
			$no++;
		}
		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->SupplierModel->count_all(),											                        "recordsFiltered" => $this->SupplierModel->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	
	public function supplier_add()
	{
	
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$data['page_name']='Add Supplier';
		$data['button_name']='Save';
		$data['type'] = $this->MainModel->get_master('master',8);
		$data['category'] = $this->MainModel->get_master('master',10);
		$w['1']='1';
		$data['states'] = $this->MainModel->get_result('states',$w);
		$this->load->view('admin/master/supplier_add',$data);
	}
	
	public function ajax_getcity()
	{
		$id = $this->input->post('id');
		$w['states_id']=$id;
		$gst = $this->MainModel->get_result('cities ',$w);
		echo '<option value="">Select</option>';
		foreach($gst as $g){
			echo '<option value="'.$g['id'].'">'.$g['city_name'].'</option>';
			}
	}
	
	public function supplier_create()
		{
			$data['party_type'] = $this->input->post('party_type');
			$data['party_category'] = $this->input->post('party_category');
			$data['party_name'] = $this->input->post('party_name');
			$data['tax_category'] = $this->input->post('tax_category');
			$data['tally_account_name'] = $this->input->post('tally_account_name');
			$data['party_designation'] = $this->input->post('party_designation');
			$data['party_contact_person_name'] = $this->input->post('party_contact_person_name');
			$data['party_email'] = $this->input->post('party_email');
			$data['party_address_1'] = $this->input->post('party_address_1');
			$data['party_mobile'] = $this->input->post('party_mobile');
			$data['party_mobile1'] = $this->input->post('party_mobile2');
			$data['party_mobile2'] = $this->input->post('party_mobile3');
			$data['party_state'] = $this->input->post('party_state');
			$data['party_city'] = $this->input->post('party_city');
			$data['party_pincode'] = $this->input->post('party_pincode');
			$data['party_phone'] = $this->input->post('party_phone');
			$data['party_phone1'] = $this->input->post('party_phone1');
			$data['party_phone2'] = $this->input->post('party_phone2');
			$data['party_area'] = $this->input->post('party_area');
			$data['party_country'] = $this->input->post('party_country');
			$data['party_websites'] = $this->input->post('party_websites');
			$data['party_gst_number'] = $this->input->post('party_gst_number');
			$data['party_pan_number'] = $this->input->post('party_pan_number');
			$data['party_msme_number'] = $this->input->post('party_msme_number');
			$data['party_beneficiary_name'] = $this->input->post('party_beneficiary_name');
			$data['party_bank_name'] = $this->input->post('party_bank_name');
			$data['party_bank_account_type'] = $this->input->post('party_bank_account_type');
			$data['party_bank_address'] = $this->input->post('party_bank_address');
			$data['party_bank_account_no'] = $this->input->post('party_bank_account_no');
			$data['party_bank_ifsc_code'] = $this->input->post('party_bank_ifsc_code');		
			
		 $sql="SELECT * FROM `sma_party_mst` WHERE (party_name='".$data['party_name']."' && del!='1')";
		$query = $this->db->query($sql);
		$count= $query->num_rows();
		if ($count>0)
		{
			$this->session->set_flashdata('this_error','Duplicate Entry Not Allowed');
			return redirect($this->supplier_add);
			
		}
		else{
			$insert = $this->MainModel->insert_row('sma_party_mst',$data);
			if($insert){
			$this->session->set_flashdata('success','Supplier Created Successfuly');
			return redirect($this->supplier);
			}else{
			$this->session->set_flashdata('error','Error');
			return redirect($this->supplier);
		   }
		}
	}
	
	public function supplier_edit($id)
	{
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$data['page_name']='Add Supplier';
		$data['button_name']='Save';
		$data['type'] = $this->MainModel->get_master('master',8);
		$data['category'] = $this->MainModel->get_master('master',10);
		$w['1']='1';$w2['id']=$id;
		$data['states'] = $this->MainModel->get_result('states',$w);
		$data['supplier'] = $this->MainModel->get_result('sma_party_mst',$w2);
		$this->load->view('admin/master/supplier_add',$data);
	}
	public function supplier_update()
		{
			$data['party_type'] = $this->input->post('party_type');
			$id=$w['id'] = $this->input->post('id');
			$data['party_category'] = $this->input->post('party_category');
			$data['party_name'] = $this->input->post('party_name');
			$data['tax_category'] = $this->input->post('tax_category');
			$data['tally_account_name'] = $this->input->post('tally_account_name');
			$data['party_designation'] = $this->input->post('party_designation');
			$data['party_contact_person_name'] = $this->input->post('party_contact_person_name');
			$data['party_email'] = $this->input->post('party_email');
			$data['party_address_1'] = $this->input->post('party_address_1');
			$data['party_mobile'] = $this->input->post('party_mobile');
			$data['party_mobile1'] = $this->input->post('party_mobile2');
			$data['party_mobile2'] = $this->input->post('party_mobile3');
			$data['party_state'] = $this->input->post('party_state');
			$data['party_city'] = $this->input->post('party_city');
			$data['party_pincode'] = $this->input->post('party_pincode');
			$data['party_phone'] = $this->input->post('party_phone');
			$data['party_phone1'] = $this->input->post('party_phone1');
			$data['party_phone2'] = $this->input->post('party_phone2');
			$data['party_area'] = $this->input->post('party_area');
			$data['party_country'] = $this->input->post('party_country');
			$data['party_websites'] = $this->input->post('party_websites');
			$data['party_gst_number'] = $this->input->post('party_gst_number');
			$data['party_pan_number'] = $this->input->post('party_pan_number');
			$data['party_msme_number'] = $this->input->post('party_msme_number');
			$data['party_beneficiary_name'] = $this->input->post('party_beneficiary_name');
			$data['party_bank_name'] = $this->input->post('party_bank_name');
			$data['party_bank_account_type'] = $this->input->post('party_bank_account_type');
			$data['party_bank_address'] = $this->input->post('party_bank_address');
			$data['party_bank_account_no'] = $this->input->post('party_bank_account_no');
			$data['party_bank_ifsc_code'] = $this->input->post('party_bank_ifsc_code');		
			
		 $sql="SELECT * FROM `sma_party_mst` WHERE (party_name='".$data['party_name']."' && del!='1') || (party_name='".$data['party_name']."' && id!='".$id."')";
		$query = $this->db->query($sql);
		$count= $query->num_rows();
		if ($count>0)
		{
			$this->session->set_flashdata('this_error','Duplicate Entry Not Allowed');
			return redirect('master/supplier/edit/'.$id);
			
		}
		else{
			$insert = $this->MainModel->update_row('sma_party_mst',$w,$data);
			if($insert){
			$this->session->set_flashdata('success','Supplier Updated Successfuly');
			return redirect($this->supplier);
			}else{
			$this->session->set_flashdata('error','Error');
			return redirect($this->supplier);
		   }
		}
	}
	public function delete_supplier($id)
		{
			$w['id']=$id;
			$data['del']=1;
			$this->MainModel->update_row('sma_party_mst',$w,$data);
			$this->session->set_flashdata('success','Supplier Deleted Successfuly');
			return redirect($this->supplier);
		}
		
	public function delete_accounts($id)
		{
			$w['id']=$id;
			$data['del']=1;
			$this->MainModel->update_row('account_mst',$w,$data);
			$this->session->set_flashdata('success','Account Deleted Successfuly');
			return redirect($this->account);
		}	
		
	public function delete_products($id)
		{
			$w['id']=$id;
			$data['del']=1;
			$this->MainModel->update_row('sma_product',$w,$data);
			$this->session->set_flashdata('success','Product Deleted Successfuly');
			return redirect($this->products);
		}
		
		
	
	
	
	
	
	/*End*/
}
