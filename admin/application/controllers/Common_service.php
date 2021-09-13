<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_service extends CI_Controller {

	
	public function __construct() {
		parent::__construct();
		$this->load->model('common_model');
	}

	public function check_username(){
		$data = $_POST;
		$res = $this->common_model->check_available($data['user_name'],$data['id']);
		echo $res;
	}

	public function check_email(){
		$data = $_POST;
		$res = $this->common_model->check_email_available($data['email'],$data['id']);
		echo $res;
	}

	public function check_phone(){
		$data = $_POST;
		$res = $this->common_model->check_phone_available($data['phone_no'],$data['id']);
		echo $res;
	}

}