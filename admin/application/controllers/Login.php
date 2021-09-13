<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {
	public function __construct() {
		parent::__construct();		
		date_default_timezone_set("Asia/Kolkata");
        $this->load->helper(array('form'));
		$this->load->model('login_model');
		if($this->session->userdata('logged_in')) { 
			redirect(base_url().'home');
		}		
 	}
	public function index(){
		$template['page_title'] = "Login";
		if(isset($_POST)) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('username', 'Username', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_database');              
			if($this->form_validation->run() == TRUE) {
				$type_id = $this->session->userdata('user_type_id');
				$array = array();
				$sql = $this->db->where('group_id',$type_id)->where('module_id',1)->get('admin_permissions')->num_rows();
				//echo $this->db->last_query();die;
				//print_r($sql);die;
				if($sql == 1){

					redirect(base_url().'home');
				 }else{
				 	if($type_id==5){
				 		
						redirect(base_url().'Vendorhome');
				 	} else {
				 		redirect(base_url().'welcome');
				 	}
				 	
				 }


			}
		}
		$this->load->view('login-form');
	}
	function check_database($password) {
		$username = $this->input->post('username');
		$result = $this->login_model->login($username, md5($password));
		
		if($result) {

			// $user=$result->id;
			// if($result->user_type_id == 1){
			// 	$user="admin";
			// }
			$sess_array = array();
			$sess_array = array(
								'id' => $result->id,
								'username' => $result->username,
								'user_id' => $result->user_id,
								'user_type_id' => $result->user_type_id,
								//'created_user' =>$user,
								);

			$sessing_arrays = array(
									'title' => 'AES'
								);
			$this->session->set_userdata('title', $sessing_arrays);
			$this->session->set_userdata('logged_in',$sess_array);
			$this->session->set_userdata('admin',$result->user_type_id);
			$this->session->set_userdata('user_id',$result->id);
			$this->session->set_userdata('user_type_id',$result->user_type_id);
			//print_r($sessing_arrays);die;
			$rs = $this->login_model->save_activity();

			return TRUE;
		}
		else {
		$this->form_validation->set_message('check_database', 'Invalid username or password');
		return false;
		}
	}
}
