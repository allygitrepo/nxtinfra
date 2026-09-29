<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SettingSubmenuModel extends CI_Model {
	var $table = 'sma_menu';

	var $column_search = array('sub_menu_name','source','menu_id','sma_main_menu.menu_name '); //set column field database for datatable searchable 
	var $order = array('id' => 'asc'); // default order 
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$this->db->select("*");
		$this->db->select("sma_main_menu.menu_name as MainMenuName ");
		$this->db->select("sma_menu.status as subStatus ");
		$this->db->select("sma_menu.order_no as OrderNum ");
		$this->db->select("sma_menu.id as subid ");
		

		//add custom filter here
		if($this->input->post('menu_id'))
		{
			$this->db->where('sma_menu.menu_id', $this->input->post('menu_id'));
		}

		$this->db->from($this->table);
		$this->db->join('sma_main_menu','sma_menu.menu_id = sma_main_menu.id');

		$i = 0;
		foreach ($this->column_search as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
	}



	public function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}


	public function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}


	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

}

