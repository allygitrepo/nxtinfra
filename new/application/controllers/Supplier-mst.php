<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'Admin.php';

class Supplier extends Admin {

	public $manage = 'supplier/manage';

	public function manage()
	{
		$where['1'] = 1;
		$data['service'] = $this->MainModel->get_result('sma_party_mst',$where);
		$where['status'] = 1;
		$this->load->view('admin/supplier/manage',$data);
		
	}

	public function add()
	{
		/*$where['1'] = 1;
		$data['page_name'] = 'Quotation';
		$data['modal_header'] = 'Edit Quotation';
		$data['service'] = $this->MainModel->get_result('service',$where);
		$data['customer'] = $this->MainModel->get_result('customer',$where);
		$data['leads'] = $this->MainModel->get_result('leads',$where);
		$where['status'] = 1;*/
		$data='';
		$this->load->view('admin/supplier/add',$data);
	}
		
	public function create()
		{
			$data['party_type'] = $this->input->post('party_type');
			$data['party_category'] = $this->input->post('party_category');
			$data['party_name'] = $this->input->post('party_name');
			$data['tax_category'] = $this->input->post('tax_category');
			$data['tally_account_name'] = $this->input->post('tally_account_name');
			//$data['party_designation'] = $this->input->post('party_designation');
			$data['party_contact_person_name'] = $this->input->post('party_contact_person_name');
			$data['party_email'] = $this->input->post('party_email');
			
			$data['party_address_1'] = $this->input->post('party_address_1');
			$data['party_mobile'] = $this->input->post('party_mobile');
			$data['party_mobile1'] = $this->input->post('party_mobile1');
			$data['party_mobile2'] = $this->input->post('party_mobile2');
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
			$insert = $this->MainModel->insert_row('sma_party_mst',$data);
			$this->session->set_flashdata('success','Created Successfuly');
			return redirect($this->manage);
			}
public function update()
	{}
	 
	 
	 public function edit($id)
	{	
		$data['page_name'] = 'Quotation';
		$data['modal_header'] = 'Edit';
		$where['id'] = $id;
		$s['1'] = 1;
		$w['country_id'] = 101;
		$data['service'] = $this->MainModel->get_result('service',$s);
		$data['states'] = $this->MainModel->get_result('states',$w);
		$data['cds'] = $this->MainModel->get_result('customer',$s);
		$data['user'] = $this->MainModel->get_row('quotation',$where);
		$this->load->view('admin/quotation/edit_quotation',$data);	
	 }


 public function delete($id)
	{
		$where['id'] = $id;
		if($this->MainModel->delete_row('quotation',$where))
		{
			$this->session->set_flashdata('success','Deleted Successfuly.');
			return redirect($this->manage);
		}
		else
		{
			$this->session->set_flashdata('failure','Error. Try again.');
			return redirect($this->manage);
		}
	}
	
	
	public function warranty()
		{
			$data['page_name'] = 'Warranty';
			$data['modal_header'] = 'Edit Warranty';
			$where['1'] = 1;
			$data['warrnty'] = $this->MainModel->get_result('warrnty',$where);
			$data['service'] = $this->MainModel->get_result('service',$where);
			$this->load->view('admin/service/warranty_manage',$data);
		}
		
	public function warranty_add()
			{
				$data['page_name'] = 'Warranty';
				$data['modal_header'] = 'Edit Warranty';
				$where['1'] = 1;
				$data['service'] = $this->MainModel->get_result('service',$where);
				$this->load->view('admin/service/add_warranty',$data);
			}

	public function warrnty_create()
		{
			$data['days'] = $this->input->post('days');
			$data['service_id'] = $this->input->post('service_id');
			$data['certificate_no'] = $this->input->post('certificate_no');
			$insert = $this->MainModel->insert_row('warrnty',$data);
			if($insert){
			$this->session->set_flashdata('success','Created Successfuly');
			return redirect($this->warrnty);}
			
	}
	public function warranty_delete($id)
	{
		$where['id'] = $id;
		if($this->MainModel->delete_row('warrnty',$where))
		{
			$this->session->set_flashdata('success','Deleted Successfuly.');
			return redirect($this->source);
		}
		else
		{
			$this->session->set_flashdata('failure','Error. Try again.');
			return redirect($this->source);
		}
	}
	
	public function warranty_edit()
	{
			$data['days'] = $this->input->post('days');
			$data['service_id'] = $this->input->post('service_id');
			$data['certificate_no'] = $this->input->post('certificate_no');
			$where['id'] = $this->input->post('edit_id');
		$query = $this->MainModel->update_row('warrnty',$where,$data);
			if($query)
		{			
			$this->session->set_flashdata('success','Details Updated Successfuly');
			return redirect($this->warrnty);
		}
		else
		{
			$this->session->set_flashdata('failure','Invalid Attempt Try Again');
			return redirect($this->warrnty);
		}	
	}
	
	
	public function amc()
		{
			$data['page_name'] = 'AMC';
			$data['modal_header'] = 'Edit AMC';
			$where['1'] = 1;
			$data['warrnty'] = $this->MainModel->get_result('amc',$where);
			$data['service'] = $this->MainModel->get_result('service',$where);
			$this->load->view('admin/service/amc',$data);
		}
	
	public function add_amc()
			{
				$data['page_name'] = 'AMC';
				$data['modal_header'] = 'Edit AMC';
				$where['1'] = 1;
				$data['service'] = $this->MainModel->get_result('service',$where);
				$this->load->view('admin/service/add_amc',$data);
			}
	
	public function amc_create()
			{
				$data['amc_detail'] = $this->input->post('amc_detail');
				$data['service_id'] = $this->input->post('service_id');
				$data['start_date'] = $this->input->post('start_date');
				$data['todal_days'] = $this->input->post('todal_days');
				$data['times'] = $this->input->post('times');
				$insert = $this->MainModel->insert_row('amc',$data);
				if($insert){
				$this->session->set_flashdata('success','Created Successfuly');
				return redirect($this->amc);}
			}
			
	public function amc_edit()
	{
			$data['amc_detail'] = $this->input->post('amc_detail');
			$data['service_id'] = $this->input->post('service_id');
			$data['start_date'] = $this->input->post('start_date');
			$data['todal_days'] = $this->input->post('todal_days');
			$data['times'] = $this->input->post('times');
			$where['id'] = $this->input->post('edit_id');
		$query = $this->MainModel->update_row('amc',$where,$data);
			if($query)
		{			
			$this->session->set_flashdata('success','Details Updated Successfuly');
			return redirect($this->amc);
		}
		else
		{
			$this->session->set_flashdata('failure','Invalid Attempt Try Again');
			return redirect($this->amc);
		}	
	}
	
	public function amc_delete($id)
	{
		$where['id'] = $id;
		if($this->MainModel->delete_row('amc',$where))
		{
			$this->session->set_flashdata('success','Deleted Successfuly.');
			return redirect($this->source);
		}
		else
		{
			$this->session->set_flashdata('failure','Error. Try again.');
			return redirect($this->source);
		}
	}
	
	
//END

}