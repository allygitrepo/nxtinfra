<?php
class MenuModel extends CI_Model {
	public function getMenu()
	{
		$where['status'] = 1;
		return $this->MainModel->get_result_orderby_menu('sma_main_menu',$where);
	}
	public function getSubMenu($data)
	{	
		foreach($data as $d){
		$sql="SELECT * FROM sma_menu WHERE menu_id='".$d['id']."' ORDER BY order_no ASC";
		$query = $this->db->query($sql);
		$rnt[$d['id']]=$query->result() ;
		}
		return $rnt;
	}
	
}

?>