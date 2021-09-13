<?php 
class Permission_model extends CI_Model {
	public function _consruct(){
		parent::_construct();
	}



	function get_roles(){
		$query = $this->db->get('admin_roles');
		$result = $query->result();
		return $result;
	}

	
	function get_all_modules($id){
		$result = $this->db->order_by('module_name','ASC')->get('module')->result();
		return $result;
	}




}
