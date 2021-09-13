<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Settings_model extends CI_Model {
	public function _consruct(){
		parent::_construct();
   }
   /* === View Settings === */
	 function settings_viewing(){
	 	  $session_data = $this->session->userdata('logged_in');
	 	  $user_id =  $session_data['user_id'];
		  $query = $this->db->query(" SELECT * FROM `system_settings` where vendor_id = $user_id ")->row();
		  return $query ;
	}
	
	 /* === Get  Settings Details === */
	public function get_single_settings($id){ 
	    $query = $this->db->where('id',$id);
	    $query = $this->db->get('system_settings');
	    $result = $query->row();
	    return $result;  
	}	

	
	 /* === Update  Settings === */

	public function update_settings($data){
	    $result = $this->db->update('system_settings', $data); 

	           		$log = array(
                         'id' =>1,
                         'log' => 'System Settings Updated'
                      );

            $session_data = $this->session->userdata('logged_in');
            $res = updatelog($log,$session_data);


		return $result;
	}


}
?>