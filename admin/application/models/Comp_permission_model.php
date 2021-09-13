<?php 
class Comp_permission_model extends CI_Model {
	public function _consruct(){
		parent::_construct();
	}



	function get_company_users(){
		$session      = $this->session->userdata('logged_in');
        $user_type_id = $session['user_type_id'];
        $user_id      = $session['user_id'];
        $result       = array();
        if ($user_type_id == 1) {
            $result = $this->db->select('company_users.*,company.company_name')->from('company_users')->join('company','company.id=company_users.comp_id')->order_by('company.company_name','ASC')->get()->result();
        } else if ($user_type_id == 3 || $user_type_id == 8 || $user_type_id == 2) {
            $result = $this->db->query("SELECT company_users.*,company.company_name FROM company_users INNER JOIN company ON FIND_IN_SET(company.id,company_access.companies) WHERE user_id=" . $user_id)->result();
        } else {
            $result = array();
        }
        return $result;
	}

	
	function get_all_modules($id){
		$result = $this->db->order_by('module_name','ASC')->get('module')->result();
		return $result;
	}

}

?>
