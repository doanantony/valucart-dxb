<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_model extends CI_Model {

	
	public function __construct() {
		parent::__construct();
	}

	public function check_available($data,$id){
		$res = $this->db->query("SELECT COUNT(*) AS num_res FROM `users` WHERE username = '$data' AND id!= '$id' ")->row();
		return $res->num_res;
	}

	public function check_email_available($data,$id){
		$res = $this->db->query("SELECT COUNT(*) AS num_res FROM `technician` WHERE email = '$data' AND id!= '$id' ")->row();
		return $res->num_res;
	}

	public function check_phone_available($data,$id){
		$res = $this->db->query("SELECT COUNT(*) AS num_res FROM `technician` WHERE phone_no = '$data' AND id!= '$id' ")->row();
		return $res->num_res;
	}

}