<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		if(!$this->session->userdata('logged_in')) {
			redirect(base_url());
		}
 	}
	
	function index() {
			$date_time = date('Y-m-d H:i:s');
            $session_data = $this->session->userdata('logged_in');
            $user_ip = get_client_ip();
            $activity_data = array(
                                    'user_id' => $session_data['id'], // id of the user done the activity
                                    'user_type_id' => $session_data['user_type_id'], //id of the usertype
                                    'date_time' => $date_time, //time of activity
                                    //'object_id' => $object_id,
                                    'log' => 'Logged Out', //action
                                   // 'edited_id' => $id, //particular id of activity done
                                    'ip_adress' => $user_ip, //ip of user who done the activity
                                    'status' => '1'  //by default
                                    );
            $res = insert_user_activity($activity_data);


		$this->session->unset_userdata('logged_in');
		session_destroy();
		redirect(base_url());
	}
}
