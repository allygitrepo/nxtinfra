<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'Admin.php';

class Setting extends Admin {

	public $mainmenu = 'setting/mainmenu';
	public $submenu = 'setting/submenu';

	public function manage_mainmenu()
	{	
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$where['1'] = 1;
		$data['mainmenu'] = $this->MainModel->get_result('sma_main_menu',$where);
		$this->load->view('admin/setting/mainmenu',$data);
	}

	public function create_mainmenu()
	{   
	
		$data['menu_name'] = $this->input->post('menu_name');
		$data['order_no'] = $this->input->post('order_no');
		$data['status'] = $this->input->post('status');
		if($data['status']!=1){$data['status']=0;}
	 $sql="SELECT * FROM sma_main_menu WHERE (menu_name='".$data['menu_name']."' && status=2)|| (  order_no='".$data['order_no']."' &&  status=2) ";
	
		$query = $this->db->query($sql);
		$count= $query->num_rows();
		if ($count>0)
		{
			$this->session->set_flashdata('this_error','Duplicate Menu Name OR Duplicate Order Number Not Allowed');
			$this->manage_mainmenu();
		}
		else
		{
		$insert = $this->MainModel->insert_row('sma_main_menu',$data);
		if($insert){
		$this->session->set_flashdata('success','Menu Created Successfuly');
		return redirect($this->mainmenu);
		}
		}
	}

	public function update_mainmenu()
		{   
		
			$data['menu_name'] = $this->input->post('name');
			$data['order_no'] = $this->input->post('order_no');
			$data['status'] = $this->input->post('status');
			$w['id']=$this->input->post('id');
 $sql="SELECT * FROM sma_main_menu WHERE (menu_name='".$data['menu_name']."' && id!='".$w['id']."')|| (  order_no='".$data['order_no']."' &&  id!='".$w['id']."') ";
		
			$query = $this->db->query($sql);
			$count= $query->num_rows();
			if ($count>0)
			{
				$this->session->set_flashdata('this_error','Duplicate Menu Name OR Duplicate Order Number Not Allowed');
				$this->manage_mainmenu();
			}
			else
			{
			$u = $this->MainModel->update_row('sma_main_menu',$w,$data);
			if($u){
			$this->session->set_flashdata('success','Menu Updated Successfuly');
			return redirect($this->mainmenu);
			}
			}
		}

		public function delete_mainmenu($id)
		{
			$where['id'] = $id;	
			$query = $this->MainModel->delete_row('sma_main_menu',$where);
		if($query)
			{			
				$this->session->set_flashdata('success','Menu Deleted Successfuly');
				return redirect($this->mainmenu);
			}
			else
			{
				$this->session->set_flashdata('failure','Error. Try again.');
				return redirect($this->mainmenu);
			}		
		}
	
	public function submenu()
	{	
		$this->load->model('MenuModel');
		$data['sidemainmenu']=$this->MenuModel->getMenu();
		$data['sidesubmenu']=$this->MenuModel->getSubMenu($data['sidemainmenu']);
		$where['1'] = 1;
		$data['mainmenu'] = $this->MainModel->get_result('sma_main_menu',$where);
		$this->load->view('admin/setting/submenu',$data);
	}
	
	public function ajax_submenu_list()
	{
		$this->load->model('SettingSubmenuModel');
		$list = $this->SettingSubmenuModel->get_datatables();
		$w['status'] = 1;/*List of staff*/
		$staff = $this->MainModel->get_result('admin',$w);
		$data = array();$no=1;
		foreach ($list as $r) {
			$row = array();
			$row[] = $no;
			$row[] = $r->MainMenuName;
			$row[] = $r->sub_menu_name;
			$row[] = $r->OrderNum;
			if($r->subStatus=='Y'){$row[] ='Active';}else{$row[] ='Inactive';;}
			$action='
			<input type="hidden" value="'.$r->sub_menu_name.'" id="sub_name_'.$r->subid.'">
			<input type="hidden" value="'.$r->source.'" id="sub_source_'.$r->subid.'">
			<input type="hidden" value="'.$r->MainMenuName.'" id="main_name_'.$r->subid.'">
			<input type="hidden" value="'.$r->target.'" id="sub_target_'.$r->subid.'">
			<input type="hidden" value="'.$r->subStatus.'" id="sub_status_'.$r->subid.'">
			<input type="hidden" value="'.$r->OrderNum.'" id="sub_ordernum_'.$r->subid.'">
			<a data-bs-toggle="modal" onclick="edit_fn('.$r->subid.','.$r->menu_id.','.$r->OrderNum.')" data-bs-target="#editmodel" class="btn btn-primary px-4 radius-30"><i class="fa fa-edit"></i> Edit</a> <a href="'.site_url('setting/delete_submenu/'.$r->subid).'" onclick="return confirm("Are you sure ?")" class="btn btn-danger px-4 radius-30"><i class="fa fa-times"></i> Delete</a>';
			$row[] = $action;
			$data[] = $row;
			$no++;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->SettingSubmenuModel->count_all(),											                        "recordsFiltered" => $this->SettingSubmenuModel->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	
	public function create_submenu()
	{   
		$data['menu_id'] = $this->input->post('main_menu');
		$data['sub_menu_name'] = $this->input->post('sub_menu_name');
		$data['order_no'] = $this->input->post('order_no');
		$data['source'] = $this->input->post('source');
		$data['target'] = $this->input->post('target');
		$data['status'] = $this->input->post('status');
		if($data['status']!='Y'){$data['status']='N';}
	    $sql="SELECT * FROM sma_menu WHERE (sub_menu_name='".$data['sub_menu_name']."' && menu_id='".$data['menu_id']."')|| (  order_no='".$data['order_no']."' && menu_id='".$data['menu_id']."') ";
		$query = $this->db->query($sql);
		$count= $query->num_rows();
		if ($count>0)
		{
			$this->session->set_flashdata('this_error','Duplicate Menu Name OR Duplicate Order Number Not Allowed');
			$this->submenu();
		}
		else
		{
		$insert = $this->MainModel->insert_row('sma_menu',$data);
		if($insert){
		$this->session->set_flashdata('success','Menu Created Successfuly');
		return redirect($this->submenu);
		}
		}
	}
	

	public function ajax_update_submenu()
	{   
		$data['sub_menu_name'] = $this->input->post('name');
		$data['source'] = $this->input->post('source');
		$data['order_no'] = $this->input->post('order_no');
		$data['menu_id'] = $this->input->post('manuid');
		$data['target'] = $this->input->post('target');
		$data['status'] = $this->input->post('status');
		$w['id'] = $this->input->post('id');
	    $sql="SELECT * FROM sma_menu WHERE (sub_menu_name='".$data['sub_menu_name']."' && menu_id='".$data['menu_id']."' && id!='".$w['id']."')|| (  order_no='".$data['order_no']."' && menu_id='".$data['menu_id']."'  && id!='".$w['id']."') ";
		$query = $this->db->query($sql);
		$count= $query->num_rows();
		if ($count>0)
		{echo 0;}
		else
		{echo 1;
		$this->MainModel->update_row('sma_menu',$w,$data);
		
		}
		}
	public function delete_submenu($id)
		{
			$where['id'] = $id;	
			$query = $this->MainModel->delete_row('sma_menu',$where);
		if($query)
			{			
				$this->session->set_flashdata('success','Sub Menu Deleted Successfuly');
				return redirect($this->submenu);
			}
			else
			{
				$this->session->set_flashdata('failure','Error. Try again.');
				return redirect($this->submenu);
			}		
		}	

	
//END
}
