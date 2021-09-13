<?php 
class Module_model extends CI_Model {
	public function _consruct(){
		parent::_construct();
	}



	function get_module(){
		$query = $this->db->get('module');
		$result = $query->result();
		return $result;
	}

	function get_function(){
		$query = $this->db->get('function');
		$result = $query->result();
		return $result;
	}

	function save_module($data,$object_id) {
		$module_name = $data['module_name'];
		$this->db->where('module_name', $module_name);
		$this->db->from('module');
		$count = $this->db->count_all_results();

		if($count > 0) {
			return "Exist";
		}
		else {

			$user_ip = get_client_ip();
            $date_time = date('Y-m-d H:i:s');
            $session_data = $this->session->userdata('logged_in');

			$rs = $this->db->where('id',$data['module_menu'])->get('object')->row();
			$module_menu = $rs->type;
			$data['object_id'] = $data['module_menu'];
			$data['module_menu'] = $module_menu;
			$result = $this->db->insert('module', $data); 
			$insert_id = $this->db->insert_id();
			$activity_data = array(
                                    'user_id' => $session_data['id'], // id of the user done the activity
                                    'user_type_id' => $session_data['user_type_id'], //id of the usertype
                                    'date_time' => $date_time, //time of activity
                                    'object_id' => $object_id,
                                    'log' => 'New Module Created', //action
                                    'edited_id' => $insert_id, //particular id of activity done
                                    'ip_adress' => $user_ip, //ip of user who done the activity
                                    'status' => '1'  //by default
                                    );
            $res = insert_user_activity($activity_data);


			if($res) {
				return "Success";
			}
			else {
				return "Error";
			}
		}
	}


	function save_function($data,$object_id) {

		$function_name = $data['function_name'];
		$this->db->where('function_name', $function_name);
		$this->db->from('function');
		$result = $this->db->insert('function', $data); 
		$insert_id = $this->db->insert_id();
			$user_ip = get_client_ip();
            $date_time = date('Y-m-d H:i:s');
            $session_data = $this->session->userdata('logged_in');	

            $activity_data = array(
                                    'user_id' => $session_data['id'], // id of the user done the activity
                                    'user_type_id' => $session_data['user_type_id'], //id of the usertype
                                    'date_time' => $date_time, //time of activity
                                    'object_id' => $object_id,
                                    'log' => 'New Function Created', //action
                                    'edited_id' => $insert_id, //particular id of activity done
                                    'ip_adress' => $user_ip, //ip of user who done the activity
                                    'status' => '1'  //by default
                                    );
            $res = insert_user_activity($activity_data);


			if($res) {
				return "Success";
			}
			else {
				return "Error";
			}
		
	}


	function get_single_module($id) {

		$query = $this->db->where('id', $id);
		$query = $this->db->get('module');
		$result = $query->row();
		return $result;

	}	



	function update_module($data, $id,$object_id) {

		$module_name = $data['module_name'];
		$this->db->where('module_name', $module_name);
		$this->db->where("id !=",$id);
		$this->db->from('module');
		$count = $this->db->count_all_results();
		if($count > 0) {
			return "Exist";
		}
		else {

			$user_ip = get_client_ip();
            $date_time = date('Y-m-d H:i:s');
            $session_data = $this->session->userdata('logged_in');

			$this->db->where('id',$id);
			$result = $this->db->update('module', $data); 

			 $activity_data = array(
                                    'user_id' => $session_data['id'], // id of the user done the activity
                                    'user_type_id' => $session_data['user_type_id'], //id of the usertype
                                    'date_time' => $date_time, //time of activity
                                    'object_id' => $object_id,
                                    'log' => 'Existing Module Updated', //action
                                    'edited_id' => $id, //particular id of activity done
                                    'ip_adress' => $user_ip, //ip of user who done the activity
                                    'status' => '1'  //by default
                                    );

			$res = insert_user_activity($activity_data);
			if($res) {
				return "Success";
			}
			else {
				return "Error";
			}
		}
	}


	function get_single_function($id) {

		$query = $this->db->where('id', $id);
		$query = $this->db->get('function');
		$result = $query->row();
		return $result;

	}	


	function update_function($data, $id,$object_id) {
		
			$this->db->where('id',$id);
			$result = $this->db->update('function', $data); 
			$user_ip = get_client_ip();
            $date_time = date('Y-m-d H:i:s');
            $session_data = $this->session->userdata('logged_in');

            $activity_data = array(
                                    'user_id' => $session_data['id'], // id of the user done the activity
                                    'user_type_id' => $session_data['user_type_id'], //id of the usertype
                                    'date_time' => $date_time, //time of activity
                                    'object_id' => $object_id,
                                    'log' => 'Existing Function Updated', //action
                                    'edited_id' => $id, //particular id of activity done
                                    'ip_adress' => $user_ip, //ip of user who done the activity
                                    'status' => '1'  //by default
                                    );
            $res = insert_user_activity($activity_data);


			if($res) {
				return "Success";
			}
			else {
				return "Error";
			}
		
	}





}
